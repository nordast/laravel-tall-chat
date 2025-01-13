<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Volt::route('/chat', 'chat.index')->name('chat.index');
    Volt::route('/chat/{query}', 'chat.chat')->name('chat.view');

    Volt::route('/users', 'users')->name('users.index');
});
