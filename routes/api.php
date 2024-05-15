<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthUserController;
use App\Http\Controllers\Api\TwoFactorController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/auth/Signup',[AuthUserController::class,'createUser']);
Route::post('/auth/login', [AuthUserController::class, 'loginUser']);
//
// Route::get('/auth/ve')->middleware('two_factor');
///
Route::get('/auth/logout',[AuthUserController::class,'logout'])->middleware('auth:sanctum');
Route::get('/auth/refreshToken',[AuthUserController::class,'refreshToken'])->middleware('auth:sanctum');


// Route::post('/auth/verifiy', [AuthUserController::class, 'verifiy']);

// Route::resource('verify',TwoFactorController::class);

