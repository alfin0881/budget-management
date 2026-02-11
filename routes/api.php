<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BudgetPlanningController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('/category', CategoryController::class)->middleware('auth:sanctum');
Route::apiResource('/budget-planning', BudgetPlanningController::class)->middleware('auth:sanctum');
Route::apiResource('/transaction', TransactionController::class)->middleware('auth:sanctum');