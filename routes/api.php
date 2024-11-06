<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WPTiendaController;
use App\Http\Controllers\ErpController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('orders', [WPTiendaController::class, 'getOrders']); 
Route::get('orders/{id}', [WPTiendaController::class, 'getOrder']); 
Route::post('orders', [WPTiendaController::class, 'createOrder']); 
Route::delete('orders/{id}', [WPTiendaController::class, 'deleteOrder']);
Route::put('orders/{id}', [WPTiendaController::class, 'updateOrder']); 



Route::post('/orders/{id}/send', [ErpController::class, 'sendOrder']);



Route::get('/test-connection', [WPTiendaController::class, 'testConnection']);



/* 
Route::get('/erp/check-order/{id}', [WPErpController::class, 'checkOrderExists']);
Route::post('/erp/send-order', [WPErpController::class, 'sendOrderToErp']); */