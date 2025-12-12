<?php

namespace Modules\User\Livewire\Session;

use Livewire\Component;
use Modules\User\Models\MentorshipSession;
use Modules\User\Patterns\Command\SessionCommandInvoker;
use Modules\User\Patterns\Services\SessionService;
use Modules\User\Patterns\Actions\CancelSessionCommand;
use Modules\User\Patterns\Validation\SessionValidation;
use Illuminate\Support\Facades\Auth;

class ShowSession extends Component
{
    public $session;
    public $role;
    public $isSubmitting = false;

    public function mount($id)
    {
        $user = auth()->user();
        $this->role = $user->role;

        SessionValidation::authorizeSessionOwner($user->id, $this->role, $id);

        $this->session = MentorshipSession::findOrFail($id);
    }


    public function cancelSession(): void
    {
        if ($this->session->status !== 'scheduled') {
            $this->dispatch('show-notification',
                type: 'error',
                message: 'Only scheduled sessions can be cancelled.'
            );
            return;
        }

        if ($this->role === 'mentee' && $this->session->mentee_id !== Auth::id()) {
            $this->dispatch('show-notification',
                type: 'error',
                message: 'You cannot cancel this session.'
            );
            return;
        }

        $this->isSubmitting = true;
        $service = new SessionService();
        $invoker = new SessionCommandInvoker(Auth::id());

        $command = new CancelSessionCommand($service, [
            'id' => $this->session->id,
            'cancelled_by' => Auth::id()
        ]);

        $result = $invoker->execute($command);
        if ($result['success']) {
            $this->dispatch('show-notification',
                type: 'success',
                message: $result['message']
            );
            $this->session = $this->session->fresh();
        } else {
            $this->dispatch('show-notification',
                type: 'error',
                message: $result['message']
            );
        }
        $this->isSubmitting = false;
    }

    public function rescheduleSession(): void
    {
        if ($this->role === 'mentee' && $this->session->mentee_id !== Auth::id()) {
            $this->dispatch('show-notification',
                type: 'error',
                message: 'You cannot reschedule this session.'
            );
            return;
        }

        if ($this->session->status !== 'cancelled') {
            $this->dispatch('show-notification',
                type: 'error',
                message: 'Only cancelled sessions can be rescheduled.'
            );
            return;
        }

        $this->session->status = 'scheduled';
        $this->session->save();

        $this->dispatch('show-notification',
            type: 'success',
            message: 'Session successfully rescheduled.'
        );

        $this->session = $this->session->fresh();
    }

    public function undo()
    {
        $invoker = new SessionCommandInvoker(Auth::id());
        $result = $invoker->undo();

        $this->dispatch('show-notification',
            type: $result['success'] ? 'success' : 'error',
            message: $result['message']
        );

        $this->session = $this->session->fresh();
    }

    public function render()
    {
        return view('user::livewire.session.show-session');
    }
}
