<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers;
use App\Supports\SQ;
use App\Supports\ResponseCode;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn () => SQ::response([],ResponseCode::HTTP_OK , 'Succeess Running',1))->name('home');



Route::prefix('v1')
    ->middleware('api_key')
    ->group(function () {
        Route::get('401', fn () => SQ::response([],ResponseCode::HTTP_UNAUTHORIZED , 'Unauthenticated',1))->name('401');

        Route::post('login', [Controllers\AuthController::class, 'login'])->name('login');


        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::get('account', [Controllers\AccountController::class, 'index'])->name('account.index');
                Route::post('account/logout', [Controllers\AccountController::class, 'logout'])->name('account.logout');
                Route::post('account/refresh_token', [Controllers\AccountController::class, 'refresh_token'])->name('account.refresh_token');

                // User
                Route::get('user', [Controllers\UserController::class, 'index'])->name('user.index');
                Route::post('user', [Controllers\UserController::class, 'store'])->name('user.store');
                Route::get('user/{id}', [Controllers\UserController::class, 'show'])->name('user.show');
                Route::post('user/{id}', [Controllers\UserController::class, 'update'])->name('user.update');
                Route::post('user/delete/{id}', [Controllers\UserController::class, 'destroy'])->name('user.destroy');
            });
    });
