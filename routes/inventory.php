<?php

use Illuminate\Support\Facades\Route;

Route::prefix('inventory')->group(function(){
    Route::get('/', [App\Http\Controllers\InventoryController::class, 'index']);
    Route::get('/{code}', [App\Http\Controllers\InventoryController::class, 'show']);
    Route::post('/', [App\Http\Controllers\InventoryController::class, 'store']);
    Route::put('/', [App\Http\Controllers\InventoryController::class, 'update']);
});