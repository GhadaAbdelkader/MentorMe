<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\UserProfileViewer;
use App\Livewire\Admin\AdminProfile;
use App\Livewire\Mentor\MentorProfile;
use App\Livewire\Mentee\MenteeProfile;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/user/{userId}/profile', UserProfileViewer::class)
    ->middleware(['auth', 'verified'])
    ->name('profile.show');

Route::middleware(['auth'])->group(function () {

    Route::get('/admin/profile', \App\Livewire\Admin\AdminProfile::class)->name('admin.profile');
    Route::get('/mentor/profile', \App\Livewire\Mentor\MentorProfile::class)->name('mentor.profile');
    Route::get('/mentee/profile', \App\Livewire\Mentee\MenteeProfile::class)->name('mentee.profile');

});
Route::get('user-management', \App\Livewire\Admin\UserManagement::class)
    ->middleware(['auth'])
    ->name('user-management');

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {

    Route::view('/', 'dashboard')->name('admin.dashboard');

    Route::get('/mentor/{userId}/profile', function ($userId) {
        return view('profile', ['userId' => $userId]);
    })->name('admin.mentor.profile');
});

require __DIR__.'/auth.php';
