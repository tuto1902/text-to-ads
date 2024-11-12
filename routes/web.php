<?php

use App\Livewire\Pages\CreateAd;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/ads/create', CreateAd::class)
    ->middleware(['auth'])
    ->name('ads.create');

require __DIR__.'/auth.php';
