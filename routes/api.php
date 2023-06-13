<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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

//login
Route::post('login', [\App\Http\Controllers\API\AuthUserController::class, 'login']);
//register
Route::post('register', [App\Http\Controllers\API\AuthUserController::class, 'register']);
//get all user
Route::get('getAllUser', [App\Http\Controllers\API\UserController::class, 'getAllUser']);
//get all user by id
Route::get('getUserById/{id}', [App\Http\Controllers\API\UserController::class, 'getUserById']);
//category
Route::get('category', [\App\Http\Controllers\API\CategoryController::class, 'index']);
//cayegory create
Route::post('category', [App\Http\Controllers\API\CategoryController::class, 'create'])->middleware('auth:sanctum');
//slider
Route::get('slider', [App\Http\Controllers\API\SliderController::class, 'index']);
//slider create
Route::post('slider', [App\Http\Controllers\API\SliderController::class, 'create'])->middleware('auth:sanctum');
// destroy
Route::delete('category/{id}', [App\Http\Controllers\API\CategoryController::class, 'destroy'])->middleware('auth:sanctum');
//show category
Route::get('category/{id}', [\App\Http\Controllers\API\CategoryController::class, 'show']);
//news
Route::get('news', [\App\Http\Controllers\API\NewsController::class, 'index']);
// news show
Route::get('news/{id}', [\App\Http\Controllers\API\NewsController::class, 'show']);
