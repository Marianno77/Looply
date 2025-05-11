<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\calendarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskInstanceController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::resource('/tasks', TaskController::class);
Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
Route::post('/task-instance/{id}/status', [TaskInstanceController::class, 'updateStatus']);
Route::get('/results', [\App\Http\Controllers\ResultsController::class, 'show'])->name('results');

#Login and register system:

Route::get('/register',[RegisterController::class,'show'])->name('register');
Route::post('/register',[RegisterController::class,'store']) ->name('auth.store');

Route::get('/login',[LoginController::class,'show'])->name('login');
Route::post('/login',[LoginController::class,'authenticate'])->name('auth.authenticate');

Route::post('/logout',[LogoutController::class,'logout'])->name('logout');

Route::post('/profle/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
