<?php

use App\Livewire\Pages\Checkout;
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

Route::get('/ads/checkout/{ad}', Checkout::class)
    ->middleware(['auth'])
    ->name('ads.checkout');

require __DIR__.'/auth.php';
