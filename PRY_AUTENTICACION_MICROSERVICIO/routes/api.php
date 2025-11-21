<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Registro (opcional)
Route::post('/register', [AuthController::class, 'register']);

// Login
Route::post('/login', [AuthController::class, 'login']);

// Validación de token (requiere token Sanctum)
Route::middleware('auth:sanctum')->get('/validate-token', [AuthController::class, 'validateToken']);
