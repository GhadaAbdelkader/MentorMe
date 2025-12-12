<?php

namespace Modules\User\Livewire\Session;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Modules\User\Models\User;
use Modules\User\Patterns\Actions\ScheduleSessionCommand;
use Modules\User\Patterns\Command\SessionCommandInvoker;
use Modules\User\Patterns\Services\SessionService;
use Illuminate\Support\Facades\Auth;

#[Title('Create New Session')]
class CreateSession extends Component
{
    #[Validate('required|integer|exists:users,id')]
    public $mentee_id = '';

    #[Validate('required|date_format:Y-m-d\TH:i')]
    public $scheduled_at = '';

    #[Validate('required|integer|min:15|max:480')]
    public $duration = 60;

    #[Validate('nullable|string|max:1000')]
    public $notes = '';

    public $isSubmitting = false;

    public function getMentees()
    {
        return User::where('id', '!=', Auth::id())
            ->where('role', 'mentee')
            ->orderBy('name')
            ->get();
    }

    public function scheduleSession()
    {
        $validated = $this->validate();
        $this->isSubmitting = true;

        try {
            $service = new SessionService();
            $invoker = new SessionCommandInvoker(Auth::id(), 'mentor');

            $command = new ScheduleSessionCommand($service, [
                'mentor_id' => Auth::id(),
                'mentee_id' => $validated['mentee_id'],
                'scheduled_at' => $validated['scheduled_at'],
                'duration' => $validated['duration'],
                'notes' => $validated['notes'] ?? null
            ]);

            $result = $invoker->execute($command);

            if ($result['success']) {
                session()->flash('success', $result['message']);
                return redirect()->route('session.show', $result['session_id']);
            } else {
                $this->addError('general', $result['message']);
            }

        } catch (\Exception $e) {
            $this->addError('general', 'error: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }

    }

    public function render()
    {
        return view('user::livewire.session.create-session', [
            'mentees' => $this->getMentees(),
            'minDateTime' => now()->addHour()->format('Y-m-d\TH:i')
        ]);
    }
}
