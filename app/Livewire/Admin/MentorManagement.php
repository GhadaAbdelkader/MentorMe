<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MentorManagement extends Component
{


    /**
     *
     * @param int $userId
     * @param string $newStatus
     */
    public function updateStatus($userId, $newStatus)
    {
        if (!Auth::check()) {
            Session()->flash('error_message', 'خطأ: يجب أن تكون مسجلاً للدخول كمسؤول.');
            return;
        }

        try {
            $response = Http::put(url('/api/users/' . $userId . '/status'), [
                'status' => $newStatus,
            ]);

            if ($response->successful()) {

                $message = match($newStatus) {
                    'active' => 'تم تفعيل حساب الموجه بنجاح.',
                    'inactive' => 'تم تعطيل حساب الموجه بنجاح.',
                    'pending' => 'تم إعادة تعيين حالة الموجه إلى معلق.',
                    default => 'تم تحديث حالة الموجه بنجاح.',
                };

                Session()->flash('message', __($message));
            } else {
                Session()->flash('error_message', 'فشل في تحديث الحالة عبر API: ' . $response->status());
            }

        } catch (\Exception $e) {
            Session()->flash('error_message', 'خطأ في الاتصال بالخادم: ' . $e->getMessage());
        }
    }

    /**
     * يتم استدعاء هذا التابع لعرض الواجهة وجلب البيانات.
     */
    public function render()
    {
        // 🌟🌟 التعديل الأساسي: جلب جميع الموجهين لعرض حالتهم 🌟🌟
        $mentors = User::where('role', 'mentor')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.admin.mentor-management', [
            'mentors' => $mentors,
        ]);
    }
}
