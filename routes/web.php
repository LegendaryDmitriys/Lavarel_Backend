<?php

use App\Http\Controllers\ProductController, App\Http\Controllers\ArraysController;
use Illuminate\Support\Facades\Route;

Route::get('/api-data', [ProductController::class, 'ApiData']);
Route::get('/arrays', [ArraysController::class, 'Arrays']);
