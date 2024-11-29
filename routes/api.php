<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WPTiendaController;
use App\Http\Controllers\ErpController;
use App\Http\Controllers\AuthController;


//rutas para el uso de JWT


/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api'); */

/* Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});
 */

Route::get('orders', [WPTiendaController::class, 'getOrders']);
Route::get('orders/{id}', [WPTiendaController::class, 'getOrder']);
Route::post('orders', [WPTiendaController::class, 'createOrder']);
Route::delete('orders/{id}', [WPTiendaController::class, 'deleteOrder']);
Route::put('orders/{id}', [WPTiendaController::class, 'updateOrder']);



Route::post('/orders/{id}/send', [ErpController::class, 'sendOrder']);

Route::post('/erp/{id}/purchase-orders', [ErpController::class, 'postToErp']);
