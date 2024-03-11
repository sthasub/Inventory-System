<?php

use App\Http\Controllers\API\UsersController;
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



Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signUp']);
Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/refreshToken', [AuthController::class, 'refreshToken']);

    // iot ->
    // api types -> soap (xml, machine), REST (application - application), GraphQL -> query language, RPC (remote procedure call) GRPC
    // RESTFull -> do not use verb in url: eg: add, create, edit, delete, handle
    // -> resources name, in plural
    // GET All -> GET /users
    // CREATE all -> POST /users
    // Update all -> PUT /users
    // Delete all -> DELETE /users
    // Get user 5 -> GET /users/5
    // Update user 5 -> PUT /users/5
    // delete all -> DELETE /users
    // delete user 5 -> DELETE /users/5
    // articles list of users 3 -> GET /users/3/articles
    // create new article for user 3 -> POST /users/3/articles
    // delete articles 11 -> DELETE /articles/11 -> /users/3/articles/11
    // all articles for all user -> GET /articles



    Route::get('/users', [UsersController::class, 'index']);
    Route::post('/users', [UsersController::class, 'addUser']);
    Route::get('/users/{id}', [UsersController::class, 'getUser']);
    Route::put('/users/{id}', [UsersController::class, 'updateUser']);
    Route::delete('/users/{id}', [UsersController::class, 'deleteUser']);
});
