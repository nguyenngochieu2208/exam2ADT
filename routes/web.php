<?php

use App\Http\Controllers\OAuthController;
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
    return view('index');
})->name('index');

Route::post('/install', [OAuthController::class, 'handleInstall'])->name('install');
Route::post('/renew-token', [OAuthController::class, 'renewToken'])->name('renew-token');
Route::post('/call-api', [OAuthController::class, 'callApi'])->name('call-api');
