<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::group(['prefix' => 'kpi', 'as' => 'kpi.'], function () {
        Route::view('/', 'kpi.index')->name('index');
        Route::view('/1', 'kpi.show')->name('show');
    });

    Route::group(['prefix' => 'project', 'as' => 'project.'], function () {
        Route::get('/', [App\Http\Controllers\ProjectController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\ProjectController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\ProjectController::class, 'store'])->name('store');
        Route::post('/{project}/change-status', [App\Http\Controllers\ProjectController::class, 'changeStatus'])->name('changeStatus');
        Route::post('/upload-file', [App\Http\Controllers\ProjectController::class, 'uploadFile'])->name('uploadFile');
        Route::delete('/{project}/delete-file/{fileKey}', [App\Http\Controllers\ProjectController::class, 'deleteFile'])->name('deleteFile');
        Route::get('/{project}', [App\Http\Controllers\ProjectController::class, 'show'])->name('show');
        Route::get('/{project}/edit', [App\Http\Controllers\ProjectController::class, 'edit'])->name('edit');
        Route::put('/{project}', [App\Http\Controllers\ProjectController::class, 'update'])->name('update');
        Route::delete('/{project}', [App\Http\Controllers\ProjectController::class, 'destroy'])->name('destroy');

        Route::post('/{project}/wbs-items', [App\Http\Controllers\ProjectWbsItemController::class, 'store'])->name('wbs-items.store');
        Route::put('/wbs-items/{wbsItem}', [App\Http\Controllers\ProjectWbsItemController::class, 'update'])->name('wbs-items.update');
        Route::patch('/wbs-items/{wbsItem}/toggle', [App\Http\Controllers\ProjectWbsItemController::class, 'toggle'])->name('wbs-items.toggle');
        Route::delete('/wbs-items/{wbsItem}', [App\Http\Controllers\ProjectWbsItemController::class, 'destroy'])->name('wbs-items.destroy');
    });

    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\UserController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\UserController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'prospect', 'as' => 'prospect.'], function () {
        Route::get('/', [App\Http\Controllers\ProspectController::class, 'index'])->name('index');
        Route::get('/create-empty', [App\Http\Controllers\ProspectController::class, 'createEmpty'])->name('createEmpty');
        Route::get('/create/{id}', [App\Http\Controllers\ProspectController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\ProspectController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\ProspectController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\ProspectController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\ProspectController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\ProspectController::class, 'destroy'])->name('destroy');

    });

    Route::group(['prefix' => 'prospect-log', 'as' => 'prospect-log.'], function () {
        Route::post('/', [App\Http\Controllers\ProspectLogController::class, 'store'])->name('store');

    });

    Route::group(['prefix' => 'quotation', 'as' => 'quotation.'], function () {
        Route::get('/', [App\Http\Controllers\QuotationController::class, 'index'])->name('index');
        Route::get('/create/{prospect}', [App\Http\Controllers\QuotationController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\QuotationController::class, 'store'])->name('store');
        Route::get('/{quotation}', [App\Http\Controllers\QuotationController::class, 'show'])->name('show');
        Route::get('/{quotation}/edit', [App\Http\Controllers\QuotationController::class, 'edit'])->name('edit');
        Route::put('/{quotation}', [App\Http\Controllers\QuotationController::class, 'update'])->name('update');
        Route::delete('/{quotation}', [App\Http\Controllers\QuotationController::class, 'destroy'])->name('destroy');

        Route::get('/{quotation}/pdf', [App\Http\Controllers\QuotationController::class, 'generatePDF'])->name('pdf');
    });

    Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
        Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('index');
        Route::get('/trashed', [App\Http\Controllers\ProductController::class, 'trashed'])->name('trashed');
        Route::post('/', [App\Http\Controllers\ProductController::class, 'store'])->name('store');
        Route::post('/import', [App\Http\Controllers\ProductController::class, 'importProduct'])->name('import');
        Route::put('/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/restore', [App\Http\Controllers\ProductController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [App\Http\Controllers\ProductController::class, 'forceDelete'])->name('force-delete');
        Route::get('/datatable/api', [App\Http\Controllers\ProductController::class, 'dataTableAPI'])->name('datatable.api');
        Route::get('/search', [App\Http\Controllers\ProductController::class, 'search'])->name('search');
        // Select options endpoints for filters
        Route::get('/brands', [App\Http\Controllers\ProductController::class, 'brands'])->name('brands');
        Route::get('/distributors', [App\Http\Controllers\ProductController::class, 'distributors'])->name('distributors');
    });

    Route::group(['prefix' => 'division', 'as' => 'division.'], function () {
        Route::get('/', [App\Http\Controllers\DivisionController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\DivisionController::class, 'store'])->name('store');
        Route::put('/{division}', [App\Http\Controllers\DivisionController::class, 'update'])->name('update');
        Route::delete('/{division}', [App\Http\Controllers\DivisionController::class, 'destroy'])->name('destroy');
        Route::get('/datatable', [App\Http\Controllers\DivisionController::class, 'datatable'])->name('datatable');
    });

    Route::group(['prefix' => 'prospect-status', 'as' => 'prospect-status.'], function () {
        Route::get('/', [App\Http\Controllers\ProspectStatusController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\ProspectStatusController::class, 'store'])->name('store');
        Route::put('/{prospectStatus}', [App\Http\Controllers\ProspectStatusController::class, 'update'])->name('update');
        Route::delete('/{prospectStatus}', [App\Http\Controllers\ProspectStatusController::class, 'destroy'])->name('destroy');
        Route::get('/datatable', [App\Http\Controllers\ProspectStatusController::class, 'datatable'])->name('datatable');
    });

    Route::group(['prefix' => 'installation-category', 'as' => 'installation-category.'], function () {
        // Route::get('/', [App\Http\Controllers\InstallationCategoryController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\InstallationCategoryController::class, 'store'])->name('store');
        Route::put('/{installation}', [App\Http\Controllers\InstallationCategoryController::class, 'update'])->name('update');
        Route::delete('/{installation}', [App\Http\Controllers\InstallationCategoryController::class, 'destroy'])->name('destroy');
        Route::get('/datatable', [App\Http\Controllers\InstallationCategoryController::class, 'datatable'])->name('datatable');
        Route::get('/search', [App\Http\Controllers\InstallationCategoryController::class, 'search'])->name('search');
    });

    Route::group(['prefix' => 'installation', 'as' => 'installation.'], function () {
        Route::get('/', [App\Http\Controllers\InstallationController::class, 'index'])->name('index');
        Route::get('/create/{quotation}', [App\Http\Controllers\InstallationController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\InstallationController::class, 'store'])->name('store');
        Route::put('/{installation}', [App\Http\Controllers\InstallationController::class, 'update'])->name('update');
        Route::delete('/{installation}', [App\Http\Controllers\InstallationController::class, 'destroy'])->name('destroy');
        Route::get('/datatable', [App\Http\Controllers\InstallationController::class, 'datatable'])->name('datatable');
        Route::get('/search', [App\Http\Controllers\InstallationController::class, 'search'])->name('search');
    });

    Route::group(['prefix' => 'accommodation-category', 'as' => 'accommodation-category.'], function () {
        Route::get('/', [App\Http\Controllers\AccommodationCategoryController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\AccommodationCategoryController::class, 'store'])->name('store');
        Route::put('/{accommodation}', [App\Http\Controllers\AccommodationCategoryController::class, 'update'])->name('update');
        Route::delete('/{accommodation}', [App\Http\Controllers\AccommodationCategoryController::class, 'destroy'])->name('destroy');
        Route::get('/datatable', [App\Http\Controllers\AccommodationCategoryController::class, 'datatable'])->name('datatable');
        Route::get('/search', [App\Http\Controllers\AccommodationCategoryController::class, 'search'])->name('search');
    });

    Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
        Route::get('/', [App\Http\Controllers\SettingController::class, 'index'])->name('index');
    });

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::put('/currency-exchange', [App\Http\Controllers\SettingController::class, 'updateCurrencyExchange'])->name('currency-exchange.update');
    });
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard/team/{teamId}', [App\Http\Controllers\HomeController::class, 'getTeamData'])->name('dashboard.team');
