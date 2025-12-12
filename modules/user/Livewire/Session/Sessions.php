<?php

namespace Modules\User\Livewire\Session;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\User\Models\MentorshipSession;
use Illuminate\Support\Facades\Auth;
use Modules\User\Patterns\Command\SessionCommandInvoker;
use Modules\User\Patterns\Services\SessionService;
use Modules\User\Patterns\Actions\DeleteSessionCommand;
use Modules\User\Patterns\Actions\CancelSessionCommand;

class Sessions extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $filterStatus = 'all';
    public $role;

    protected $listeners = ['sessionUpdated' => 'refresh'];

    public function mount()
    {
        if (!Auth::check()) abort(401);
        $this->role = Auth::user()->role;
    }

    public function getSessions()
    {
        $query = MentorshipSession::query();

        if ($this->role === 'mentor') {
            $query->where('mentor_id', Auth::id());
        } else {
            $query->where('mentee_id', Auth::id());
        }

        if ($this->searchTerm) {
            $query->whereHas('mentee', fn($q) => $q->where('name', 'like', "%{$this->searchTerm}%"));
        }

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        return $query->with('mentee')->orderBy('scheduled_at', 'desc')->paginate(10);
    }

    public function cancelSession($sessionId): void
    {
        $session = MentorshipSession::findOrFail($sessionId);

        if ($this->role === 'mentee' && $session->mentee_id !== Auth::id()) {
            session()->flash('error', 'You cannot cancel this session.');
            return;
        }

        if ($session->status !== 'scheduled') {
            session()->flash('error', 'Only scheduled sessions can be cancelled.');
            return;
        }

        $service = new SessionService();
        $invoker = new SessionCommandInvoker(Auth::id());

        $command = new CancelSessionCommand($service, [
            'id' => $session->id,
            'cancelled_by' => Auth::id()
        ]);

        $result = $invoker->execute($command);

        session()->flash($result['success'] ? 'success' : 'error', $result['message']);
        $this->refresh();
    }

    public function undo(): void
    {
        $invoker = new SessionCommandInvoker(Auth::id());
        $result = $invoker->undo();

        session()->flash($result['success'] ? 'success' : 'error', $result['message']);
        $this->refresh();
    }
    public function deleteSession($sessionId): void
    {
        $session = MentorshipSession::findOrFail($sessionId);

        $session->delete();


        session()->flash('success', 'Session deleted successfully');
    }

    public function refresh(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('user::livewire.session.sessions', [
            'sessions' => $this->getSessions(),
            'statuses' => [
                'all' => 'All',
                'scheduled' => 'Scheduled',
                'modified' => 'Modified',
                'cancelled' => 'Canceled',
                'completed' => 'Completed'
            ]
        ]);
    }
}
