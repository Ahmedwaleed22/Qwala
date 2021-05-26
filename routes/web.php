<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TheoreticalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
[*] Required To Authenticate Pages
*/
Route::middleware(['auth', 'web'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name("home");
    Route::post('/participate', [HomeController::class, 'participate']);
});

/*
[*] Login Routes
*/
Route::get('/login', [AuthenticationController::class, 'login'])->name("login");
Route::post('/login', [AuthenticationController::class, 'authenticate']);

/*
[*] Register Routes
*/
Route::get('/register', [AuthenticationController::class, 'register'])->name("register");
Route::post('/register', [AuthenticationController::class, 'store']);

Route::get('/logout', [AuthenticationController::class, 'logout'])->name("logout");

/*
[*] Theoretical Questions Routes
*/
Route::get('/theoretical/questions/{topic_id}', [TheoreticalController::class, 'questions']);
