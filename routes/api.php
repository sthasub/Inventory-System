<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

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

Route::middleware('auth:sanctum')->get('user', [AuthController::class,'user']);



Route::post('login',[AuthController::class,'login']);
Route::post('forget-password',[AuthController::class,'forgetPassword']);
Route::post('reset-password',[AuthController::class,'resetPassword']);
Route::middleware('auth:sanctum')->post('logout',[AuthController::class,'logout']);
Route::middleware('auth:sanctum')->get('refreshToken',[AuthController::class,'refreshToken']);
