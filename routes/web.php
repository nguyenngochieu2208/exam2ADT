<?php

use App\Http\Controllers\ContactController;
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

Route::prefix('/')->middleware('token')->name('contact.')->group(function () {
    Route::get('/', [ContactController::class, 'list'])->name('list');

    Route::get('/create', [ContactController::class, 'create'])->name('create');

    Route::post('/add', [ContactController::class, 'add'])->name('add');

    Route::get('/edit/{contact_id?}/{requisite_id?}/{bank_id?}', [ContactController::class, 'edit'])->name('edit');

    Route::post('/update', [ContactController::class, 'update'])->name('update');

    Route::post('/delete', [ContactController::class, 'delete'])->name('delete');
});

// Route::get('/getToken', [OAuthController::class, 'getToken'])->name('getToken');
Route::post('/install', [OAuthController::class, 'handleInstall'])->name('install');

