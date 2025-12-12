<?php

use Illuminate\Support\Facades\Route;
use App\Mail\MyEmail;
use Illuminate\Support\Facades\Mail;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/testroute', function() {
    $name = "Funny Coder";

    // The email sending is done using the to method on the Mail facade
    Mail::to('testreceiver@gmail.com')->send(new MyEmail($name));
});

require __DIR__.'/auth.php';
