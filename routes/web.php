<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/api-data', [ProductController::class, 'ApiData']);
