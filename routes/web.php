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

Route::get('/', fn() => SQ::response([], ResponseCode::HTTP_OK, 'Succeess Running', 1))->name('home');



Route::prefix('v1')
    ->middleware('api_key')
    ->group(function () {
        Route::get('401', fn() => SQ::response([], ResponseCode::HTTP_UNAUTHORIZED, 'Unauthenticated', 1))->name('401');

        Route::post('login', Controllers\Auth\Login::class)->name('login');

        Route::middleware('auth:sanctum')
            ->group(function () {
                // Account
                $module = 'account';
                Route::get($module, Controllers\Account\Show::class)->name($module . '.show');
                Route::get($module . '/update_password', Controllers\Account\UpdatePassword::class)->name($module . '.update_password');
                Route::get($module . '/logout', Controllers\Account\Logout::class)->name($module . '.logout');
                Route::get($module . '/refresh_token', Controllers\Account\RefreshToken::class)->name($module . '.refresh_token');

                // User
                $module = 'user';
                Route::get($module, Controllers\User\Index::class)->name($module . '.index');
                Route::post($module, Controllers\User\Store::class)->name($module . '.store');
                Route::get($module . '/{id}', Controllers\User\Show::class)->name($module . '.show');
                Route::post($module . '/{id}', Controllers\User\Update::class)->name($module . '.update');
                Route::post($module . '/update_password/{id}', Controllers\User\UpdatePassword::class)->name($module . '.update_password');
                Route::post($module . '/delete/{id}', Controllers\User\Destroy::class)->name($module . '.destroy');
                Route::post($module . '/delete_bulk', Controllers\User\DestroyBulk::class)->name($module . '.destroy_bulk');
            });
    });
