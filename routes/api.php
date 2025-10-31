<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SentryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// API Version 1
Route::prefix('v1')->group(function () {
    
    // Public routes (no authentication needed)
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    });

    // Health check route
    Route::get('/health', function () {
        return response()->json([
            'status' => 'healthy',
            'version' => '1.0',
            'timestamp' => now()
        ]);
    });

    // Protected routes (authentication required)
    Route::middleware(['auth:sanctum'])->group(function () {
        
        // Auth routes
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/logout-all', [AuthController::class, 'logoutAll']);
            Route::get('/user', [AuthController::class, 'user']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
            Route::post('/change-password', [AuthController::class, 'changePassword']);
        });

        // Profile routes
        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'show']);
            Route::put('/', [ProfileController::class, 'update']);
            Route::post('/avatar', [ProfileController::class, 'updateAvatar']);
            Route::delete('/avatar', [ProfileController::class, 'deleteAvatar']);
        });

        // Sentry error summary endpoint (cached)
            Route::get('/sentry/errors', [SentryController::class, 'errors']);
            // Analytics - graduates
            Route::get('/analytics/graduates', [\App\Http\Controllers\Api\AnalyticsController::class, 'graduates']);
        // User management routes (admin only)
        Route::middleware(['role:admin'])->prefix('users')->group(function () {
            // List and statistics
            Route::get('/statistics', [UserController::class, 'statistics']);
            Route::get('/export', [UserController::class, 'export']);
            
            // Bulk operations
            Route::post('/bulk-delete', [UserController::class, 'bulkDelete']);
            Route::post('/bulk-status', [UserController::class, 'bulkUpdateStatus']);
            
            // Individual user operations
            Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus']);
            Route::post('/{user}/restore', [UserController::class, 'restore'])->withTrashed();
            Route::delete('/{user}/force', [UserController::class, 'forceDelete'])->withTrashed();
            
            // Standard CRUD routes
            Route::apiResource('/', UserController::class)->parameters(['' => 'user']);
        });
    });
});


    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});

Route::middleware(['auth:sanctum', 'role:developer,admin'])->group(function () {
    Route::get('/projects', [ProjectController::class, 'index']);
});

// Sentry error summary endpoint (cached)
// (moved into v1 auth group)


use App\Http\Controllers\MondayController;

Route::prefix('monday')->group(function () {
    Route::get('/tickets', [MondayController::class, 'getTickets']);
    Route::get('/tickets/{id}', [MondayController::class, 'getTicketById']);
    Route::get('/tickets/search', [MondayController::class, 'searchTickets']);
});

