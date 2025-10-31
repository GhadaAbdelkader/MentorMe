<?php

namespace App\Livewire\Auth;

use App\Livewire\Actions\Logout;
use App\Services\EmailVerificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class VerifyEmail extends Component
{

    public ?string $testVerificationLink = null;

    public function sendVerification(EmailVerificationService $verificationService): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
            return;
        }

        $verificationService->sendVerification();

        Session::flash('status', 'verification-link-sent');

        // Generate test link only in local mode
        $this->testVerificationLink = $verificationService->generateTestLink();
    }

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }

    public function render(EmailVerificationService $verificationService)
    {
        $this->testVerificationLink = $verificationService->generateTestLink();

        return view('livewire.auth.verify-email')->layout('layouts.guest');
    }
}
