<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\Api\VenueController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('venues', [VenueController::class, 'index']);
Route::get('venues/{id}', [VenueController::class, 'show']);
Route::get('categories', [CategoryController::class, 'index']);
Route::post('quotes', [QuoteController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('favorites', [FavoriteController::class, 'index']);
    Route::post('favorites/toggle', [FavoriteController::class, 'toggle']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth:api', 'admin'], 'prefix' => 'admin'], function () {
    // Venue Admin CRUD
    Route::post('venues', [VenueController::class, 'store']);
    Route::put('venues/{id}', [VenueController::class, 'update']);
    Route::delete('venues/{id}', [VenueController::class, 'destroy']);

    // Category Admin CRUD
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

    // Quote Admin Management
    Route::get('quotes', [QuoteController::class, 'index']);
    Route::put('quotes/{id}', [QuoteController::class, 'update']);
    Route::delete('quotes/{id}', [QuoteController::class, 'destroy']);
});
