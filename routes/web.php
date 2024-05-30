<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ArraysController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/api-data', [ProductController::class, 'ApiData']);
Route::get('/arrays', [ArraysController::class, 'arrays']);
Route::get('/search', [SearchController::class, 'dataSearch']);
