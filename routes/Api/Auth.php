<?php

use App\Http\Controllers\Api\AuthUserController;
use Illuminate\Support\Facades\Route;
// use Illuminate\Http\Request;

Route::controller(AuthUserController::class)
    ->prefix('auth')
    ->group(function(){

        Route::middleware('guest:sanctum')
            ->group(function(){
                Route::post('Signup','createUser')->name('auth.Signup');
                Route::post('verifyEemail','verifyEemail')->name('auth.verifyEemail');
                Route::post('login', 'loginUser')->name('auth.loginUser');
            }); 

        Route::middleware('auth:sanctum')
            ->group(function(){
                Route::get('logout','logout')->name('auth.logout');
                Route::get('refreshToken','refreshToken')->name('auth.refreshToken');
            });
        
        Route::post('DeleteUserAccount', 'DeleteUserAccount')->name('auth.DeleteUserAccount');
    });