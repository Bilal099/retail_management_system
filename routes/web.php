<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TempleteController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\InvoiceController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [AuthenticatedSessionController::class, 'create']);

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::controller(AjaxController::class)->prefix('ajax')->group(function () {
        Route::get('/getProductDetails', 'getProductDetails')->name('ajax.getProductDetails');
        Route::get('/getTransactionByMerchant', 'getTransactionByMerchant')->name('ajax.getTransactionByMerchant');
    });

    Route::controller(TransactionController::class)->prefix('transaction')->group(function () {
        Route::get('/maker-checker/{id}', 'makerChecker')->name('transaction.makerChecker');
        Route::get('/cancel-transaction/{id}', 'cancelTransaction')->name('transaction.cancelTransaction');
        Route::get('/expense-list', 'transactionExpenseList')->name('transaction.expenseList');
        Route::get('/expense-create', 'transactionExpenseCreate')->name('transaction.expenseCreate');
        route::post('/expense-store', 'transactionExpenseStore')->name('transaction.expenseStore');
        route::get('/expense-show/{id}', 'transactionExpenseShow')->name('transaction.expenseShow');
    });
    
    Route::controller(InvoiceController::class)->prefix('invoices')->group(function () {
        Route::get('/maker-checker/{id}', 'makerChecker')->name('invoices.makerChecker');
        
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('merchant', MerchantController::class);
    Route::resource('product', ProductController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('inventories', InventoryController::class);
    Route::resource('invoices', InvoiceController::class);
});

require __DIR__.'/auth.php';

Route::get('/{page}', [TempleteController::class,'index']);
