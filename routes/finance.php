<?php

use Illuminate\Support\Facades\Route;

Route::prefix('finance')->group(function(){
    Route::get('/', [App\Http\Controllers\FinanceController::class, 'index']);
    Route::post('/', [App\Http\Controllers\FinanceController::class, 'store']);
});