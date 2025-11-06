<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Livewire\Auth\Login;
use Modules\User\Livewire\Auth\Register;
use Modules\User\Livewire\Admin\UserProfileViewer;
use Modules\User\Livewire\Admin\AdminProfile;
use Modules\User\Livewire\Mentor\MentorProfile;
use Modules\User\Livewire\Mentee\MenteeProfile;
use Modules\User\Livewire\Admin\UserManagement;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/user/{userId}/profile', UserProfileViewer::class)
        ->middleware(['verified'])
        ->name('profile.show');

    Route::get('/admin/profile', AdminProfile::class)->name('admin.profile');
    Route::get('/mentor/profile', MentorProfile::class)->name('mentor.profile');
    Route::get('/mentee/profile', MenteeProfile::class)->name('mentee.profile');


});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {

    Route::view('/', 'dashboard')->name('admin.dashboard');

    Route::get('/mentor/{userId}/profile', function ($userId) {
        return view('profile', ['userId' => $userId]);
    })->name('admin.mentor.profile');
    Route::get('/user-management', UserManagement::class)
        ->name('user-management');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/user/{userId}/profile', UserProfileViewer::class)
    ->middleware(['auth', 'verified'])
    ->name('profile.show');
