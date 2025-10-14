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

// Simpan data baru (dummy)
Route::post('/quotation/store', function () {
    return redirect()->route('quotation.index');
})->name('quotation.store');

// Update data (dummy)
Route::put('/quotation/{id}/update', function ($id) {
    return redirect()->route('quotation.index');
})->name('quotation.update');


Route::get('/quotation/create', function () {
    return view('quotation.create');
})->name('quotation.create');

Route::get('/quotation/{id}/edit', function ($id) {
    $quotation = (object)[
        'id' => $id,
        'client_name' => 'John Doe',
        'project_name' => 'Website Development',
        'total_amount' => 3000,
        'status' => 'pending',
        'notes' => 'Initial draft quotation.'
    ];
    return view('quotation.edit', compact('quotation'));
})->name('quotation.edit');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/quotation', function () {
    // Dummy data sementara buat lihat tampilan
    $quotations = collect([
        (object)[
            'id' => 1,
            'quotation_number' => 'Q-2025-001',
            'client_name' => 'John Doe',
            'project_name' => 'Website Development',
            'total_amount' => 3456,
            'status' => 'approved',
            'created_at' => now(),
        ],
        (object)[
            'id' => 2,
            'quotation_number' => 'Q-2025-002',
            'client_name' => 'Jane Smith',
            'project_name' => 'Mobile App Design',
            'total_amount' => 2100,
            'status' => 'pending',
            'created_at' => now(),
        ],
    ]);
    return view('quotation.index', compact('quotations'));
})->name('quotation.index');

Route::get('/quotation/detail', function () {
    $quotation = (object)[
        'id' => 1,
        'quotation_number' => 'Q-2025-001',
        'client_name' => 'John Doe',
        'project_name' => 'Website Development',
        'subtotal' => 3150,
        'tax' => 315,
        'total_amount' => 3465,
        'created_at' => now(),
        'items' => [
            (object)['description' => 'UI Design', 'quantity' => 1, 'unit_price' => 1000, 'total' => 1000],
            (object)['description' => 'Frontend Development', 'quantity' => 1, 'unit_price' => 1500, 'total' => 1500],
            (object)['description' => 'Backend API', 'quantity' => 1, 'unit_price' => 650, 'total' => 650],
        ]
    ];
    return view('quotation.detail', compact('quotation'));
})->name('quotation.detail');