<?php

use App\Http\Controllers\Web\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/login', WelcomeController::class)->name('login');

Route::get('/', WelcomeController::class);
