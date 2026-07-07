<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContentController;

Route::get('/', [ContentController::class, 'home'])->name('home');
Route::get('/{category}/{slug}', [ContentController::class, 'show'])->name('content.show');
