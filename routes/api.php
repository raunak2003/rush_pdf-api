<?php

use App\Http\Controllers\authController;
use App\Http\Controllers\fileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/google/redirect', [authController::class, 'google_redirect'])->name('google_redirect');
Route::get('/google/oauth2callback', [authController::class, 'google_callback'])->name('google_callback');
Route::post('file_upload',[fileController::class,'store']);
Route::post('list',[fileController::class,'list']);
Route::delete('delete/{id}',[fileController::class,'delete']);