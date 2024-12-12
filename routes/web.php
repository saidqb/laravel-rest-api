<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers;
use Saidqb\LaravelSupport\SQ;
use Saidqb\LaravelSupport\ResponseCode;
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

Route::get('/', fn() => response()->view('home'))->name('home');



Route::prefix('v1')
    ->middleware(['api_key'])
    ->group(function () {
        Route::get('401', fn() => SQ::response([], ResponseCode::HTTP_UNAUTHORIZED, 'Unauthenticated', 1))->name('401');

        Route::post('login', Controllers\Auth\Login::class)->name('login');

        Route::middleware(['auth:sanctum', 'api_role'])
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
                Route::post($module . '/update_password/{id}', Controllers\User\UpdatePassword::class)->name($module . '.update_password');
                Route::post($module . '/delete/{id}', Controllers\User\Destroy::class)->name($module . '.destroy');
                Route::post($module . '/delete_bulk', Controllers\User\DestroyBulk::class)->name($module . '.destroy_bulk');
                Route::get($module . '/{id}', Controllers\User\Show::class)->name($module . '.show');
                Route::post($module . '/{id}', Controllers\User\Update::class)->name($module . '.update');


                // User_role
                $module = 'user_role';
                Route::get($module, Controllers\User_role\Index::class)->name($module . '.index');
                Route::post($module, Controllers\User_role\Store::class)->name($module . '.store');
                Route::post($module . '/delete/{id}', Controllers\User_role\Destroy::class)->name($module . '.destroy');
                Route::post($module . '/delete_bulk', Controllers\User_role\DestroyBulk::class)->name($module . '.destroy_bulk');
                Route::get($module . '/permission', Controllers\User_role\Permission::class)->name($module . '.permission');
                Route::get($module . '/{id}', Controllers\User_role\Show::class)->name($module . '.show');
                Route::post($module . '/{id}', Controllers\User_role\Update::class)->name($module . '.update');
            });
    });


Route::prefix('media')
    ->middleware(['app_access'])
    ->group(function () {

        // upload
        $module = 'upload';
        Route::post($module . '/file', Controllers\Upload\File::class)->name($module . '.file');
        Route::post($module . '/image', Controllers\Upload\Image::class)->name($module . '.image');
        Route::post($module . '/document', Controllers\Upload\Document::class)->name($module . '.document');


        $module = 'uploads';
        Route::post($module . '/file', Controllers\Uploads\File::class)->name($module . '.file');
        Route::post($module . '/image', Controllers\Uploads\Image::class)->name($module . '.image');
        Route::post($module . '/document', Controllers\Uploads\Document::class)->name($module . '.document');
    });


Route::middleware(['file_access','frame_guard'])
    ->group(function () {

        // upload
        $module = 'file';
        Route::any($module . '/v/{fid}', Controllers\Serving\FileServing::class)->name($module . '.private')
            ->where('fid', '.*');


        Route::any($module . '/ov/{fid}', Controllers\Serving\FileOldServing::class)->name($module . '.private')
            ->where('fid', '.*');
    });
