<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\DaController;
use App\Http\Controllers\KeystoreController;
use App\Http\Controllers\MarketDevController;
use App\Http\Controllers\MarketsController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TemplateController;
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


Route::get('/project-aio/{id}', [ProjectController::class, 'getProject']);

Route::get('/getDa', [ApiController::class, 'getDa'])->name('api.getDa');
Route::get('/getTemplate', [ApiController::class, 'getTemplate'])->name('api.getTemplate');
Route::get('/getDev', [ApiController::class, 'getDev'])->name('api.getDev');
Route::get('/getKeystore', [ApiController::class, 'getKeystore'])->name('api.getKeystore');
Route::get('/getGa', [ApiController::class, 'getGa'])->name('api.getGa');


