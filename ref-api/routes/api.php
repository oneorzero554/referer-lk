<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\ProvidersController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\SitesController;
use App\Http\Controllers\SourcesController;
use Illuminate\Support\Facades\Route;

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


Route::controller(AuthController::class)->prefix('auth')->group(function() {
    Route::post('registration', 'registration');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::get('refresh', 'refresh');
    Route::get('me', 'me');
});

Route::middleware('auth:api')->group(function() {
    Route::controller(SitesController::class)->prefix('sites')->group(function() {
        Route::get('list', 'list');
    });
    Route::controller(CitiesController::class)->prefix('cities')->group(function() {
        Route::get('list', 'list');
    });
    Route::controller(RequestsController::class)->prefix('requests')->group(function() {
        Route::get('index', 'index');
        Route::post('create', 'create');
        Route::post('update', 'update');
    });
    Route::controller(CategoriesController::class)->prefix('categories')->group(function() {
        Route::get('list', 'list');
    });
    Route::controller(ProvidersController::class)->prefix('providers')->group(function() {
        Route::get('list', 'list');
    });
    Route::controller(SourcesController::class)->prefix('sources')->group(function() {
        Route::get('list', 'list');
    });
});

