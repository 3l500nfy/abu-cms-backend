<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/complaints/categories', [ComplaintController::class, 'categories']);
Route::get('/complaints/statuses', [ComplaintController::class, 'statuses']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);
    
    // User routes
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    
    // Complaint routes
    Route::apiResource('complaints', ComplaintController::class);
    
    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [UserController::class, 'dashboard']);
        Route::get('/admin/users', [UserController::class, 'index']);
        Route::get('/admin/analytics', [UserController::class, 'analytics']);
    });
});
