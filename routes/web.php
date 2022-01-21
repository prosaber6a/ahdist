<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserSettingController;

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
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';


Route::middleware('auth')->group(function () {
    Route::resource('party', PartyController::class, [
        'names' => [
            'index' => 'parties',
            'store' => 'store_party',
            'create' => 'create_party',
            'edit' => 'edit_party',
            'update' => 'update_party',
            'destroy' => 'delete_party',
        ]
    ]);

    Route::resource('product', ProductController::class, [
        'names' => [
            'index' => 'products',
            'store' => 'store_product',
            'create' => 'create_product',
            'edit' => 'edit_product',
            'update' => 'update_product',
            'destroy' => 'delete_product',
        ]
    ]);


    Route::get('api/last_month_sales_vs_purhcase', [OperationController::class, 'last_month_sales_vs_purchase']);
    Route::get('api/last_10_transaction', [TransactionController::class, 'last_10_transaction']);
    Route::get('api/operation/filter/{type}/{party}/{from}/{to}', [OperationController::class, 'filter_operation']);
    //purchase routes
    Route::get('purchase', [OperationController::class, 'index'])->name('purchases');
    Route::get('purchase/create', [OperationController::class, 'create'])->name('create_purchase');
    Route::get('purchase/{operation}/edit', [OperationController::class, 'edit'])->name('edit_purchase');
    Route::get('purchase/{operation}/show', [OperationController::class, 'edit'])->name('show_purchase');
    Route::post('purchase', [OperationController::class, 'store'])->name('store_purchase');
    Route::put('purchase/{operation}', [OperationController::class, 'update'])->name('update_purchase');
    Route::delete('purchase/{operation}', [OperationController::class, 'destroy'])->name('delete_purchase');

    //sales routes
    Route::get('sale', [OperationController::class, 'index'])->name('sales');
    Route::get('sale/create', [OperationController::class, 'create'])->name('create_sale');
    Route::get('sale/{operation}/edit', [OperationController::class, 'edit'])->name('edit_sale');
    Route::get('sale/{operation}/show', [OperationController::class, 'edit'])->name('show_sale');
    Route::post('sale', [OperationController::class, 'store'])->name('store_sale');
    Route::put('sale/{operation}', [OperationController::class, 'update'])->name('update_sale');
    Route::delete('sale/{operation}', [OperationController::class, 'destroy'])->name('delete_sale');

    // Account Routes
    Route::resource('account', AccountController::class, [
        'names' => [
            'index' => 'accounts',
            'store' => 'store_account',
            'create' => 'create_account',
            'edit' => 'edit_account',
            'update' => 'update_account',
            'destroy' => 'delete_account',
        ]
    ]);


    // Transaction Route
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::get('transaction/deposit/create', [TransactionController::class, 'create'])->name('create_deposit');
    Route::get('transaction/expense/create', [TransactionController::class, 'create'])->name('create_expense');
    Route::get('transaction/transfer/create', [TransactionController::class, 'create'])->name('create_transfer');
    Route::get('transaction/{transaction}/edit', [TransactionController::class, 'edit'])->name('edit_transaction');
    Route::post('transaction', [TransactionController::class, 'store'])->name('store_transaction');
    Route::put('transaction/{transaction}', [TransactionController::class, 'update'])->name('update_transaction');
    Route::delete('transaction/{transaction}', [TransactionController::class, 'destroy'])->name('delete_transaction');
    Route::get('api/transaction/filter/{party_id}/{acc_id}/{from}/{to}', [TransactionController::class, 'filter_transaction']);

    //User Setting
    Route::get('user-setting', [UserSettingController::class, 'current_user_setting'])->name('current_user_setting');
    Route::post('user-setting', [UserSettingController::class, 'update_current_user_setting'])->name('update_current_user_setting');
    Route::get('change-password', [UserSettingController::class, 'change_current_user_password'])->name('change_current_user_password');
    Route::post('change-password', [UserSettingController::class, 'update_current_user_password'])->name('update_current_user_password');
});



