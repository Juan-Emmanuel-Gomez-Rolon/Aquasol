<?php

use App\Http\Controllers\EntryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\WebController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/sales', [SaleController::class, 'index'])->name('sales');
    Route::get('/dashboard', [WebController::class, 'dashboard']);
    Route::get('/register', [WebController::class, 'dashboard'])->name('dashboard');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update');

    Route::get('/entries', [EntryController::class, 'web_index'])->name('entries');
    Route::get('/entries/create', [EntryController::class, 'create'])->name('entries.create');
    Route::get('/entries/{entry}', [EntryController::class, 'show'])->name('entries.show');
    Route::get('/entries/{entry}/edit', [EntryController::class, 'edit'])->name('entries.edit');

    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
    Route::get('/products/create', [ProductController::class, 'web_create'])->name('products.create');
    Route::get('/products/{product}', [ProductController::class, 'web_show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'web_edit'])->name('products.edit');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:admin,god',
])->group(function () {
    Route::get('/users', [WebController::class, 'users'])->name('users');
});

// api group for admin
Route::prefix('api')->middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:admin,god',
])->group(function () {
    Route::post('/users', [WebController::class, 'store_user']);
    Route::put('/users/{user}', [WebController::class, 'update_user']);
    Route::delete('/users/{user}', [WebController::class, 'delete_user']);
    Route::put('/users/{id}/enable', [WebController::class, 'enable_user']);
});


// api group
Route::prefix('api')->middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/barcode/{barcode}', [InventoryController::class, 'barcode']);

    Route::get('/inventory', [InventoryController::class, 'index']);
    Route::post('/inventory', [InventoryController::class, 'store']);
    Route::put('/inventory/{id}', [InventoryController::class, 'update']);
    Route::delete('/inventory/{id}', [InventoryController::class, 'destroy']);

    Route::get('/sales', [SaleController::class, 'index']);
    Route::post('/sales', [SaleController::class, 'store']);
    Route::put('/sales/{sale}', [SaleController::class, 'update']);
    Route::delete('/sales/{sale}', [SaleController::class, 'destroy']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    Route::get('/entries', [EntryController::class, 'index']);
    Route::post('/entries', [EntryController::class, 'store']);
    Route::put('/entries/{entry}', [EntryController::class, 'update']);
    Route::delete('/entries/{entry}', [EntryController::class, 'destroy']);
});
