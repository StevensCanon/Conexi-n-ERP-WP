<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ErpController;
use App\Http\Controllers\WPTiendaController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('Home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Ruta de Continuar, esto para cuando se registra por primera vez
    Route::get('/continue', [StoreController::class, 'continue'])->name('continue');

    // Rutas de perfil
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Rutas para Tiendas
    Route::resource('store', StoreController::class)->names('stores');

    // Rutas de ERP agrupadas por tienda
    Route::prefix('store/{store}/erp')->name('erp.')->group(function () {
        Route::get('/', [ErpController::class, 'index'])->name('index');
        Route::get('create', [ErpController::class, 'create'])->name('create');
        Route::post('store', [ErpController::class, 'store'])->name('store');
        Route::get('edit/{erp}', [ErpController::class, 'edit'])->name('edit');
        Route::put('update/{erp}', [ErpController::class, 'update'])->name('update');
        Route::delete('destroy/{erp}', [ErpController::class, 'destroy'])->name('destroy');
    });

    // Rutas adicionales de Store para restaurar y eliminar permanentemente
    Route::patch('stores/restore/{storeId}', [StoreController::class, 'restore'])->name('stores.restore');
    Route::delete('stores/forceDelete/{storeId}', [StoreController::class, 'forceDelete'])->name('stores.forceDelete');

    //Ruta adicional para pausar una sincronizacion a erp
    Route::post('/erp/toggle-pause/{storeId}', [ErpController::class, 'togglePause'])->name('erp.togglePause');
});

//rutas de test
Route::get('test-api-connection/{storeId}', [WPTiendaController::class, 'testApiConnection']);
Route::get('/erp/{id}/test-connection', [ErpController::class, 'testConnection']);



require __DIR__ . '/auth.php';
