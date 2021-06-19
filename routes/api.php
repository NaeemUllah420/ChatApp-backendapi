<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/signin", [UserController::class, 'signIn']);
Route::post("/signup", [UserController::class, 'signUp']);

//user's routes
Route::prefix("user")->middleware(['token'])->group(function () {
    Route::post("/home", [HomeController::class, 'home']);
    Route::post("/logout",[UserController::class,'logout']);
    Route::post("/search",[HomeController::class,'searchUser']);
});
//message's routes
Route::prefix("message")->middleware(['token'])->group(function () {
    Route::post("/send", [MessageController::class, 'sendMessage']);
    Route::post("/update", [MessageController::class, 'updateMessage']);
    Route::post("/delete", [MessageController::class, 'deleteMessage']);
    Route::post("/get", [MessageController::class, 'getMessages']);
});
//group's routes
Route::prefix("group")->middleware(['token'])->group(function () {
    Route::post("/create", [GroupController::class, 'create']);
});
Route::get("/get-files/{receiver_or_group}/{random}/{file}/{extension}", [MessageController::class, 'getPrivateFiles']);
Route::get("/storage/{receiver_or_group}/{random}/{file_with_extension}", [MessageController::class, 'getPublicFiles']);
