<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SwapRequestController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;





Route::post('/register', [AuthController::class, 'register']);
// user login and token creation
Route::post('/login', [AuthController::class, 'login']);
// get all users
Route::get('/users', [AuthController::class, 'index']);
// get specified user by id
Route::get('/users/{id}', [AuthController::class, 'show']);
// search user by name
Route::get('/users/search/{name}', [AuthController::class, 'search']);


Route::get('/user/{itemId}', [ItemsController::class, 'getUserByItemId']);

Route::get('/swap-requests/{userId}', [SwapRequestController::class, 'receivedSwapRequests']);

Route::get('/items/{itemId}', [ItemsController::class, 'getItemById']);

Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/images/{filename}', [ImageController::class, 'show']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::put('/items/{id}/{item_id}', [ItemsController::class, 'update'])->middleware('check_user_ownership');
    Route::delete('/users/{id}', [AuthController::class, 'destroy'])->middleware('check_user_ownership');
    Route::delete('/items/{id}/{item_id}', [ItemsController::class, 'destroy'])->middleware('check_user_ownership');
    Route::post('/items/{id}', [ItemsController::class, 'item_post'])->middleware('check_user_ownership');
    Route::post('/items/search', [ItemsController::class, 'searchByName'])->middleware('check_user_ownership');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::delete('/swap-requests/{swapRequestId}',  [SwapRequestController::class, 'destroy'])->middleware('auth:sanctum');
    Route::post('/swap-requests', [SwapRequestController::class, 'store'])->middleware('auth:sanctum');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
