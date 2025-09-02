<?php

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

use Illuminate\Support\Facades\Route;
use Workdo\CustomerLogin\Http\Controllers\CustomerLoginController;
use Workdo\CustomerLogin\Http\Controllers\CustomerTicketController;

Route::group(['middleware' => ['web','auth','verified','ModuleCheckEnable:CustomerLogin']], function () {

    Route::prefix('customerlogin')->group(function () {
        // 
    });
 
});
Route::group(['middleware' => ['web','ModuleCheckEnable:CustomerLogin']], function () {

    Route::prefix('customerlogin')->group(function () {
        Route::get('/create/ticket/{lang?}', [CustomerTicketController::class, 'index'])->name('create.ticket');
        Route::post('ticket', [CustomerTicketController::class, 'store'])->name('ticket.store');
    });
   

    Route::get('/register/{lang?}', [CustomerLoginController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [CustomerLoginController::class, 'store'])->name('register.store');
    
});

