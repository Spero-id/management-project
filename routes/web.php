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
        Route::get('/{project}/wbs-items/export', [App\Http\Controllers\ProjectWbsItemController::class, 'export'])
            ->name('projects.wbs-items.export');

        Route::post('/{project}/wbs-items/import', [App\Http\Controllers\ProjectWbsItemController::class, 'import'])->name('wbs-items.import');
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
        Route::post('/{id}/upload-document', [App\Http\Controllers\UserController::class, 'uploadDocument'])->name('upload-document');
    });

    Route::group(['prefix' => 'sales-order', 'as' => 'sales-order.'], function () {
        Route::get('/create/{id}', [App\Http\Controllers\SalesOrderController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\SalesOrderController::class, 'store'])->name('store');
        Route::post('/mom', [App\Http\Controllers\SalesOrderController::class, 'saveMinuteOfMeeting'])->name('saveMinuteOfMeeting');
    });

    Route::group(['prefix' => 'prospect', 'as' => 'prospect.'], function () {
        Route::get('/', [App\Http\Controllers\ProspectController::class, 'index'])->name('index');
        Route::get('/api/index', [App\Http\Controllers\ProspectController::class, 'indexApi'])->name('api.index');
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
        Route::get('/sales-target', [App\Http\Controllers\SalesTargetController::class, 'index'])->name('sales-target');
    });

    Route::group(['prefix' => 'sales-target', 'as' => 'sales-target.'], function () {
        Route::post('/', [App\Http\Controllers\SalesTargetController::class, 'store'])->name('store');
        Route::put('/{salesTarget}', [App\Http\Controllers\SalesTargetController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::put('/currency-exchange', [App\Http\Controllers\SettingController::class, 'updateCurrencyExchange'])->name('currency-exchange.update');
        Route::put('/total-jasa', [App\Http\Controllers\SettingController::class, 'updateTotalJasa'])->name('total-jasa.update');
    });

    Route::group(['prefix' => 'project-weekly-meetings', 'as' => 'project-weekly-meetings.'], function () {
        Route::get('/users', [App\Http\Controllers\ProjectWeeklyMeetingController::class, 'getUsers'])->name('users');
        Route::get('/', [App\Http\Controllers\ProjectWeeklyMeetingController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\ProjectWeeklyMeetingController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\ProjectWeeklyMeetingController::class, 'store'])->name('store');
        Route::get('/{projectWeeklyMeeting}', [App\Http\Controllers\ProjectWeeklyMeetingController::class, 'show'])->name('show');
        Route::get('/{projectWeeklyMeeting}/edit', [App\Http\Controllers\ProjectWeeklyMeetingController::class, 'edit'])->name('edit');
        Route::put('/{projectWeeklyMeeting}', [App\Http\Controllers\ProjectWeeklyMeetingController::class, 'update'])->name('update');
        Route::delete('/{projectWeeklyMeeting}', [App\Http\Controllers\ProjectWeeklyMeetingController::class, 'destroy'])->name('destroy');

        Route::get('/datatable/{projectId}', [App\Http\Controllers\ProjectWeeklyMeetingController::class, 'datatable'])->name('datatable');
        Route::get('/export/{projectId}', [App\Http\Controllers\ProjectWeeklyMeetingController::class, 'export'])->name('export');
        Route::post('/import/{projectId}', [App\Http\Controllers\ProjectWeeklyMeetingController::class, 'import'])->name('import');
    });

    // buatkan route untuk stock controller
    Route::group(['prefix' => 'stock', 'as' => 'stock.'], function () {
        Route::get('/', [App\Http\Controllers\StockController::class, 'index'])->name('index');
        Route::get('/datatable', [App\Http\Controllers\StockController::class, 'datatable'])->name('datatable');
        Route::get('/export', [App\Http\Controllers\StockController::class, 'export'])->name('export');
        Route::post('/store', [App\Http\Controllers\StockController::class, 'store'])->name('store');
        Route::get('/brands', [App\Http\Controllers\StockController::class, 'getBrands'])->name('brands');
        Route::get('/types', [App\Http\Controllers\StockController::class, 'getTypes'])->name('types');
        Route::get('/current', [App\Http\Controllers\StockController::class, 'getCurrentStock'])->name('current');
    });

    Route::group(['prefix' => 'inventory', 'as' => 'inventory.'], function () {
        Route::get('/', [App\Http\Controllers\InventoryController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [App\Http\Controllers\InventoryController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [App\Http\Controllers\InventoryController::class, 'update'])->name('update');
        Route::get('/datatable', [App\Http\Controllers\InventoryController::class, 'datatable'])->name('datatable');
    });

    Route::group(['prefix' => 'borrowing', 'as' => 'borrowing.'], function () {
        Route::get('/', [App\Http\Controllers\BorrowingController::class, 'index'])->name('index');
        Route::get('/datatable', [App\Http\Controllers\BorrowingController::class, 'datatable'])->name('datatable');
        Route::get('/create', [App\Http\Controllers\BorrowingController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\BorrowingController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\BorrowingController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\BorrowingController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\BorrowingController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\BorrowingController::class, 'destroy'])->name('destroy');
        Route::get('/data/users', [App\Http\Controllers\BorrowingController::class, 'getUsers'])->name('users');
        Route::get('/data/brands', [App\Http\Controllers\BorrowingController::class, 'getBrands'])->name('brands');
        Route::get('/data/types', [App\Http\Controllers\BorrowingController::class, 'getTypes'])->name('types');
        Route::get('/data/current-stock', [App\Http\Controllers\BorrowingController::class, 'getCurrentStock'])->name('current-stock');
        Route::get('/data/borrowed-items', [App\Http\Controllers\BorrowingController::class, 'getBorrowedItems'])->name('borrowed-items');
        Route::get('/data/borrowing-detail/{id}', [App\Http\Controllers\BorrowingController::class, 'getBorrowingDetail'])->name('borrowing-detail');
        Route::post('/return', [App\Http\Controllers\BorrowingController::class, 'returnItems'])->name('return');
    });

    Route::group(['prefix' => 'project-order', 'as' => 'project-order.'], function () {
        Route::get('/', [App\Http\Controllers\ProjectOrderController::class, 'index'])->name('index');
        Route::get('/datatable', [App\Http\Controllers\ProjectOrderController::class, 'datatable'])->name('datatable');
        Route::get('/delivery-datatable', [App\Http\Controllers\ProjectOrderController::class, 'deliveryDatatable'])->name('delivery-datatable');
        Route::post('/store', [App\Http\Controllers\ProjectOrderController::class, 'store'])->name('store');
        Route::post('/confirm/{projectId}', [App\Http\Controllers\ProjectOrderController::class, 'confirm'])->name('confirm');
        Route::post('/upload-po-logistics', [App\Http\Controllers\ProjectOrderController::class, 'uploadPOLogistics'])->name('upload-po-logistics');
    });

    Route::group(['prefix' => 'finance/project-order', 'as' => 'finance-project-order.'], function () {
        Route::get('/', [App\Http\Controllers\ProjectOrderController::class, 'FinanceIndex'])->name('index');
        Route::get('/datatable', [App\Http\Controllers\ProjectOrderController::class, 'financeDatatable'])->name('datatable');
        Route::post('/upload-po', [App\Http\Controllers\ProjectOrderController::class, 'uploadPO'])->name('upload-po');
    });

    Route::group(['prefix' => 'delivery-order', 'as' => 'delivery-order.'], function () {
        Route::get('/', [App\Http\Controllers\DeliveryOrderController::class, 'index'])->name('index');
        Route::get('/datatable', [App\Http\Controllers\DeliveryOrderController::class, 'datatable'])->name('datatable');
        Route::post('/', [App\Http\Controllers\DeliveryOrderController::class, 'store'])->name('store');
        Route::get('/project-items/{projectId}', [App\Http\Controllers\DeliveryOrderController::class, 'getProjectItems'])->name('project-items');
        Route::get('/{id}', [App\Http\Controllers\DeliveryOrderController::class, 'show'])->name('show');
    });

    // perhitungan project
    Route::group(['prefix' => 'perhitungan-project', 'as' => 'perhitungan-project.'], function () {
        Route::get('/', [App\Http\Controllers\PerhitunganProjectController::class, 'index'])->name('index');
    });
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard/team/{teamId}', [App\Http\Controllers\HomeController::class, 'getTeamData'])->name('dashboard.team');
