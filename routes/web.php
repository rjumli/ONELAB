<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'landing']);
Route::get('/verification/{code}', [App\Http\Controllers\WelcomeController::class, 'verification']);
Route::get('/csf/survey', [App\Http\Controllers\CsfController::class, 'show']);
Route::post('register', [App\Http\Controllers\WelcomeController::class, 'register']);
Route::get('new', [App\Http\Controllers\WelcomeController::class, 'new'])->name('new');

Route::middleware(['2fa','auth','verified'])->group(function () {
    Route::resource('/profile', App\Http\Controllers\User\ProfileController::class);
});

Route::middleware(['2fa','auth','verified','is_active','menu'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/customers', App\Http\Controllers\CustomerController::class);
    Route::resource('/requests', App\Http\Controllers\RequestController::class);
    Route::resource('/quotations', App\Http\Controllers\QuotationController::class);
    Route::resource('/inventory', App\Http\Controllers\InventoryController::class);
    Route::resource('/csf', App\Http\Controllers\CsfController::class);

    Route::resource('/samples', App\Http\Controllers\SampleController::class);
    Route::resource('/analyses', App\Http\Controllers\AnalysisController::class);

    Route::prefix('insights')->group(function(){
        Route::controller(App\Http\Controllers\InsightController::class)->group(function () {
            Route::get('/','index');
            Route::get('/customers','customers_view');
            Route::get('/laboratories','laboratories_view');
        });
    });

    Route::prefix('services')->group(function(){
        Route::resource('/testservices', App\Http\Controllers\Services\TestserviceController::class);
        Route::resource('/packages', App\Http\Controllers\Services\PackageController::class);
        Route::resource('/import', App\Http\Controllers\Services\ImportController::class);
    }); 
});

require __DIR__.'/auth.php';
require __DIR__.'/utility.php';
require __DIR__.'/inventory.php';
require __DIR__.'/finance.php';
