<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileViewer extends Component
{
    // 🌟🌟 تم حذف: protected static string $layout = 'layouts.app';
    // لأن المكون يتم تحميله الآن داخل View وسيط

    public $userId;
    public User $user;
    public bool $isAdminViewing = false;

    public function mount($userId)
    {
        // تحميل علاقة 'mentor' مسبقاً
        $this->user = User::with('mentorProfile')->findOrFail($userId);

        // التحقق مما إذا كان المستخدم الحالي مشرفاً
        $this->isAdminViewing = Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * التحقق مما إذا كان الملف الشخصي للموجه قد تم تعبئته.
     */
    public function isMentorProfileFilled(): bool
    {
        if ($this->user->role !== 'mentor') {
            return true;
        }

        if (!$this->user->mentor) {
            return false;
        }

        return !empty($this->user->mentor->mentor_skills) &&
            !empty($this->user->mentor->mentor_description);
    }

    public function updateStatus(string $newStatus)
    {
        // منطق تحديث الحالة (عبر API)
        session()->flash('message', "تم تحديث حالة المستخدم #{$this->user->id} إلى {$newStatus} بنجاح.");
        $this->user->status = $newStatus;
        $this->user->save();
    }

    public function render()
    {
        return view('livewire.user.profile-viewer');
    }
}
