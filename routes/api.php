<?php

use App\Http\Controllers\V1\ReportController;
use App\Http\Controllers\V1\SaleController;
use App\Http\Controllers\V1\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->middleware('auth:sanctum')->group(function() {
    // Books
    Route::get('/books', [BookController::class, 'index'])->name('api.v1.books.index');
    Route::post('/books', [BookController::class, 'store'])->name('api.v1.books.store');
    Route::get('/books/create', [BookController::class, 'create']); // For form data
    Route::get('/books/{book}', [BookController::class, 'show']);
    Route::get('/books/{book}/edit', [BookController::class, 'edit']); // For form data
    Route::put('/books/{book}', [BookController::class, 'update']);
    Route::delete('/books/{book}', [BookController::class, 'destroy']);

    // Sales
    Route::get('/sales', [SaleController::class, 'index']);
    Route::post('/sales', [SaleController::class, 'store']);
    Route::get('/sales/create', [SaleController::class, 'create']); // For form data
    Route::get('/sales/{sale}/invoice', [SaleController::class, 'generateInvoice']);

    // Reports
    Route::get('/reports/sales', [ReportController::class, 'sales']);
    Route::get('/reports/low-stock', [ReportController::class, 'lowStock']);
});