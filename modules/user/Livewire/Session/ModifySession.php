<?php

namespace Modules\User\Livewire\Session;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Modules\User\Models\MentorshipSession;
use Modules\User\Patterns\Actions\ModifySessionCommand;
use Modules\User\Patterns\Command\SessionCommandInvoker;
use Modules\User\Patterns\Services\SessionService;
use Modules\User\Patterns\Validation\SessionValidation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

#[Title('Modify Session')]
class ModifySession extends Component
{
    public $session;

    #[Validate('required|date_format:Y-m-d\TH:i')]
    public $scheduled_at = '';

    #[Validate('required|integer|min:15|max:480')]
    public $duration = 60;

    #[Validate('nullable|string|max:1000')]
    public $notes = '';

    public $isSubmitting = false;

    public function mount($id)
    {
        try {
            SessionValidation::authorizeSessionOwner(
                auth()->id(),
                auth()->user()->role,
                $id
            );

        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }

        $this->session = MentorshipSession::findOrFail($id);
        $this->scheduled_at = $this->session->scheduled_at->format('Y-m-d\TH:i');
        $this->duration = $this->session->duration;
        $this->notes = $this->session->notes ?? '';
    }

    public function updateSession()
    {
        $this->validate();
        $this->isSubmitting = true;

        try {
            $service = new SessionService();
            $invoker = new SessionCommandInvoker(Auth::id());

            $command = new ModifySessionCommand($service, [
                'id' => $this->session->id,
                'scheduled_at' => Carbon::parse($this->scheduled_at)->format('Y-m-d H:i:s'),
                'duration' => $this->duration,
                'notes' => $this->notes ?: null
            ]);

            $result = $invoker->execute($command);

            if ($result['success']) {
                session()->flash('success', $result['message']);
                return redirect()->route('session.show', $this->session->id);
            } else {
                $this->addError('general', $result['message']);
            }

        } catch (\Exception $e) {
            $this->addError('general', 'error: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }
    public function undo()
    {
        try {
            $invoker = new SessionCommandInvoker(Auth::id());
            $result = $invoker->undo();

            if ($result['success']) {
                session()->flash('success', $result['message']);
            } else {
                session()->flash('error', $result['message']);
            }

        } catch (\Exception $e) {
            session()->flash('error', 'error: ' . $e->getMessage());
        }

        return redirect()->route('session.show', $this->session->id);
    }

    public function render()
    {
        return view('user::livewire.session.edit-session', [
            'minDateTime' => now()->addHour()->format('Y-m-d\TH:i')
        ]);
    }
}
