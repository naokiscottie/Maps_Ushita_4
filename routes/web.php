<?php

use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();
Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/index', [MapController::class, 'map']);
    Route::post('/map_registration', [MapController::class, 'map_registration']);
    Route::get('/map_delete/{id}', [MapController::class, 'map_delete']);
    Route::get('/map_update/{id}', [MapController::class, 'map_update']);
    Route::post('/map_update_registration', [MapController::class, 'map_update_registration']);

    Route::post('/sheet_setting', [MapController::class, 'sheet_setting']);
    
});
