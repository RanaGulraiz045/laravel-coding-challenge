<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/random-users', [UserController::class, 'getRandomUsers'])->name('random-users');
Route::get('/user-profile/{id}', [UserController::class, 'showProfile'])->name('user-profile');
Route::get('login', function () {
    return response()->json(['message' => 'Unauthorized'], 401);
})->name('login');
