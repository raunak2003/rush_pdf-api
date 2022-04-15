<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authController;
use App\Middleware\logincheck;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/logout', function () {
    auth()->logout();
    return redirect('google_login');
})->name('logout');

Route::get('google_login',[authController::class,'login']);
Route::get('/google/redirect',[authController::class,'google_redirect'])->name('google_redirect');
Route::get('/google/callback',[authController::class,'google_callback'])->name('google_callback');