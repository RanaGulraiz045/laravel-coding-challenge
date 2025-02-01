<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\TaskController;

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
Route::post('login', [AuthController::class,'login']);
Route::post('signup', [AuthController::class,'signup']);
Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::post('/admin/create-invitation', [InvitationController::class,'createInvitation']);
    Route::post('/admin/resend-invitation', [InvitationController::class,'resendInvitation']);
    
});
Route::middleware('auth:api')->group(function () {
    Route::post('/tasks', [TaskController::class, 'createTask']);
    Route::get('/tasks', [TaskController::class, 'listTasks']);
    Route::get('/tasks/{id}', [TaskController::class, 'showTask']);
    Route::put('/tasks/{id}', [TaskController::class, 'updateTask']);
    Route::delete('/tasks/{id}', [TaskController::class, 'deleteTask']);
});
