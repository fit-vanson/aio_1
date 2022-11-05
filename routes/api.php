<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\DaController;
use App\Http\Controllers\DesignController;
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

Route::get('/getProject', [ApiController::class, 'getProject'])->name('api.getProject');
Route::get('/getDa', [ApiController::class, 'getDa'])->name('api.getDa');
Route::get('/getTemplate', [ApiController::class, 'getTemplate'])->name('api.getTemplate');
Route::get('/getDev', [ApiController::class, 'getDev'])->name('api.getDev');
Route::get('/getKeystore', [ApiController::class, 'getKeystore'])->name('api.getKeystore');
Route::get('/getGa', [ApiController::class, 'getGa'])->name('api.getGa');
Route::get('/getMarket', [ApiController::class, 'getMarket'])->name('api.getMarket');
Route::get('/getGmailDev', [ApiController::class, 'getGmailDev'])->name('api.getGmailDev');
Route::get('/getProfile', [ApiController::class, 'getProfile'])->name('api.getProfile');


Route::get('/get_admod_list', [ApiController::class, 'get_admod_list'])->name('api.get_admod_list');
Route::get('/get-gclient', [ApiController::class, 'get_gclient'])->name('api.get_gclient');
Route::get('/get-token/{id}', [ApiController::class, 'get_token'])->name('api.get_token');
Route::get('/get-token-callback', [ApiController::class, 'get_get_token_callback'])->name('api.get_get_token_callback');
Route::get('/getReview/{id}', [ApiController::class, 'getReview'])->name('api.getReview');
Route::get('/postReview/{id}', [ApiController::class, 'postReview'])->name('api.postReview');

Route::get('/get-inappproducts',[ApiController::class,'get_inappproducts'])->name('api.get_inappproducts');
Route::get('/samsung',[ApiController::class,'samsung'])->name('api.samsung');


Route::get('/picture/{param}',[DesignController::class,'picture'])->name('api.picture');





