<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\User\ProfileViewer;
use App\Livewire\Admin\MentorManagement;
use App\Livewire\Dashboard;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/user/{userId}/profile', ProfileViewer::class)
    ->middleware(['auth', 'verified'])
    ->name('profile.show');


Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {

    Route::view('/', 'dashboard')->name('admin.dashboard');

    Route::get('/mentor/{userId}/profile', function ($userId) {
        return view('profile', ['userId' => $userId]);
    })->name('admin.mentor.profile');
});

require __DIR__.'/auth.php';
