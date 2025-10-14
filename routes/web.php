<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KPIController;

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
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/kpi', [KpiController::class, 'index'])->name('kpi.index');
