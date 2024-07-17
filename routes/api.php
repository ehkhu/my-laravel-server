<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\PostController;

// use App\Http\Controllers\AuthController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('posts', PostController::class);

// Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('role:super-admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::post('/users/{user}/roles/remove', [UserController::class, 'removeRole']);
    });

    Route::middleware('role:super-admin,admin')->group(function () {
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('permissions', PermissionController::class);
        Route::post('/roles/{role}/permissions', [RoleController::class, 'assignPermission']);
        Route::post('/users/{user}/roles', [UserController::class, 'assignRole']);
        Route::post('/register', [UserController::class, 'register']);
    });

    // Route::middleware('role:user')->group(function () {
    //     // Define routes accessible to 'user' role here
    // });
});