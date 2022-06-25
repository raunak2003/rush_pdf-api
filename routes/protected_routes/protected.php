<?php

use App\Http\Controllers\fileController;
use App\Http\Controllers\fileGet;
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
Route::post('file_upload', [fileController::class, 'store'])->middleware('logincheck');
Route::get('list', [fileController::class, 'list'])->middleware('logincheck');
Route::delete('delete/{id}', [fileController::class, 'delete'])->middleware('logincheck');
Route::get('getFile/{id}',[fileGet::class,'index']);
