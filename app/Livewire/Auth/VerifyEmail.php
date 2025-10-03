<?php

namespace App\Livewire\Auth;

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class VerifyEmail extends Component
{

    public ?string $testVerificationLink = null;

    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
        $this->generateTestLink();

    }


    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    public function generateTestLink(): void
    {
        $user = Auth::user();

        // نتحقق من أننا في بيئة محلية وأن المستخدم موجود وغير موثق
        if (App::environment('local') && $user && !$user->hasVerifiedEmail()) {

            // نحتاج إلى التأكد من أن المستخدم يطبق العقد
            if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail) {
                // إنشاء نفس الرابط الذي يتم إرساله في البريد الإلكتروني
                $this->testVerificationLink = URL::temporarySignedRoute(
                    'verification.verify',
                    now()->addMinutes(60), // مدة صلاحية الرابط
                    [
                        'id' => $user->getKey(),
                        'hash' => sha1($user->getEmailForVerification()),
                    ]
                );
            }
        }
    }

    public function render()
    {
        $this->generateTestLink();

        return view('livewire.auth.verify-email')->layout('layouts.guest');
    }
}
