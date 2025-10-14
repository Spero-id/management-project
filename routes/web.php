<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/project', function () {
    return view('project_list.project');
})->name('project.index');

Route::get('/project/detail/{id}', function ($id) {
    return view('project_list.detail', compact('id'));
})->name('project.detail');

Route::get('/project/{id}/log', function($id) {
    return view('project_list.log', compact('id'));
})->name('project.log');

Route::get('/project/notes/{status}', function ($status) {
    return view('project_list.notes', compact('status'));
});
