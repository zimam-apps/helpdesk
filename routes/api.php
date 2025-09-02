<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\CustomFieldController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/home', [HomeController::class, 'index']);

    Route::post('/ticket', [TicketController::class, 'index']);
    Route::post('/ticket_create', [TicketController::class, 'store']);
    Route::post('/ticket_update', [TicketController::class, 'update']);
    Route::post('/ticket_delete', [TicketController::class, 'destroy']);

    Route::post('/openticket', [TicketController::class, 'openTicket']);
    Route::post('/replayticket', [TicketController::class, 'replayTicket']);
    Route::get('/ticketstatus', [TicketController::class, 'ticketStatus']);
    Route::get('/priority', [TicketController::class, 'getPriority']);

    Route::get('/category', [CategoryController::class, 'index']);
    Route::post('/getcategory', [CategoryController::class, 'getcategory']);
    Route::post('/create_category', [CategoryController::class, 'store']);
    Route::post('/update_category', [CategoryController::class, 'update']);
    Route::post('/delete_category', [CategoryController::class, 'destroy']);

    Route::post('/users', [UserController::class, 'index']);
    Route::post('/getuser', [UserController::class, 'getuser']);
    Route::post('/user_create', [UserController::class, 'store']);
    Route::post('/user_update', [UserController::class, 'update']);
    Route::post('/user_delete', [UserController::class, 'destroy']);
    Route::post('/edit_profile', [UserController::class, 'editProfile']);

    Route::get('/role', [RoleController::class, 'index']);
    Route::post('/role_create', [RoleController::class, 'store']);
    Route::post('/role_update', [RoleController::class, 'update']);
    Route::post('/role_delete', [RoleController::class, 'destroy']);

    Route::post('/faq', [FaqController::class, 'indexs']);
    Route::post('/faq_create', [FaqController::class, 'store']);
    Route::post('/faq_update', [FaqController::class, 'update']);
    Route::post('/faq_delete', [FaqController::class, 'destroy']);

    Route::post('/sitesetting', [SettingController::class, 'sitesetting']);
    Route::post('/site_setting_page', [SettingController::class, 'site_setting_page']);

    Route::post('/emailsettingpage', [SettingController::class, 'emailsettingpage']);
    Route::post('/emailsetting', [SettingController::class, 'emailsetting']);
    Route::post('/test_email_send', [SettingController::class, 'testEmailSend']);

    Route::post('/recaptchasetting', [SettingController::class, 'recaptchasetting']);
    Route::get('/lang', [SettingController::class, 'langList']);

    // Route::post('/getcoustomfield', [TicketController::class, 'getCoustomField']);
    // Route::post('/ticketsetting', [SettingController::class, 'CustomFields']);
    // Route::post('/coustomfield', [SettingController::class, 'storeCustomFields']);
    // Route::post('/deletecoustomfield', [SettingController::class, 'destroyCustomFields']);

    Route::post('/getcoustomfield', [CustomFieldController::class, 'getCoustomField']);
    Route::post('/ticketsetting', [CustomFieldController::class, 'CustomFields']);
    Route::post('/coustomfield_create', [CustomFieldController::class, 'storeCustomFields']);
    Route::post('/coustomfield_update', [CustomFieldController::class, 'updateCustomFields']);
    Route::post('/coustomfield_delete', [CustomFieldController::class, 'destroyCustomFields']);
});









