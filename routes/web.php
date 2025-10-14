<?php

use Illuminate\Support\Facades\Route;
// menambahkan
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::group(['prefix' => 'prospect', 'as' => 'prospect.'], function () {
        Route::view('/', 'prospect.index')->name('index');
        Route::view('/create', 'prospect.create')->name('create');
        Route::view('/1', 'prospect.show')->name('show');
        Route::view('/1/edit', 'prospect.edit')->name('edit');
    });

    // routes negotiation
    Route::prefix('negotiation')->group(function () {
        Route::view('/', 'negotiation.index')->name('negotiation.index');
        Route::view('/index', 'negotiation.index');
        Route::view('/approve', 'negotiation.approve');
        // Route::view('/detail', 'negotiation.detail');
        // Route::view('/negotiation/detail/{id}', 'negotiation.detail');
        Route::view('/detail/{id}', 'negotiation.detail');
        // Route::view('/log', 'negotiation.log');
        Route::view('/log/{id}', 'negotiation.log');
        Route::view('/reject', 'negotiation.reject');
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
