<?php

use App\Livewire\Categories\CreateCategory;
use App\Livewire\Categories\IndexCategory;
use App\Livewire\Categories\ReadCategory;
use App\Livewire\Categories\UpdateCategory;
use App\Livewire\Dashboards\IndexDashboard;
use App\Livewire\Employees\CreateEmployee;
use App\Livewire\Employees\IndexEmployee;
use App\Livewire\Employees\ReadEmployee;
use App\Livewire\Employees\UpdateEmployee;
use App\Livewire\Products\CreateProduct;
use App\Livewire\Products\IndexProduct;
use App\Livewire\Products\ReadProduct;
use App\Livewire\Products\UpdateProduct;
use App\Livewire\Purchases\CreatePurchase;
use App\Livewire\Purchases\IndexPurchase;
use App\Livewire\Purchases\ReadPurchase;
use App\Livewire\Purchases\UpdatePurchase;
use App\Livewire\Sales\CreateSale;
use App\Livewire\Sales\IndexSale;
use App\Livewire\Sales\ReadSale;
use App\Livewire\Sales\UpdateSale;
use App\Livewire\Targets\CreateTarget;
use App\Livewire\Targets\IndexTarget;
use App\Livewire\Targets\ReadTarget;
use App\Livewire\Targets\UpdateTarget;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware(['auth'])->group(function () {
    Route::get('/', IndexDashboard::class)->name('home');
    Route::get('dashboard', IndexDashboard::class)->name('dashboard');
    // Employees
    Route::prefix('employees')->name('employees.')->group(function() {
        Route::get('/', IndexEmployee::class)->name('index');
        Route::get('create', CreateEmployee::class)->name('create');
        Route::get('read/{id}', ReadEmployee::class)->name('read');
        Route::get('update/{id}', UpdateEmployee::class)->name('update');
        Route::delete('{id}', [IndexEmployee::class, 'delete'])->name('delete');
    });
    // Targets
    Route::prefix('targets')->name('targets.')->group(function() {
        Route::get('/', IndexTarget::class)->name('index');
        Route::get('create', CreateTarget::class)->name('create');
        Route::get('read/{id}', ReadTarget::class)->name('read');
        Route::get('update/{id}', UpdateTarget::class)->name('update');
        Route::delete('{id}', [IndexTarget::class, 'delete'])->name('delete');
    });
    // Categories
    Route::prefix('categories')->name('categories.')->group(function() {
        Route::get('/', IndexCategory::class)->name('index');
        Route::get('create', CreateCategory::class)->name('create');
        Route::get('read/{id}', ReadCategory::class)->name('read');
        Route::get('update/{id}', UpdateCategory::class)->name('update');
        Route::delete('{id}', [IndexCategory::class, 'delete'])->name('delete');
    });
    // Products
    Route::prefix('products')->name('products.')->group(function() {
        Route::get('/', IndexProduct::class)->name('index');
        Route::get('create', CreateProduct::class)->name('create');
        Route::get('read/{id}', ReadProduct::class)->name('read');
        Route::get('update/{id}', UpdateProduct::class)->name('update');
        Route::delete('{id}', [IndexProduct::class, 'delete'])->name('delete');
    });
    // Sales
    Route::prefix('sales')->name('sales.')->group(function() {
        Route::get('/', IndexSale::class)->name('index');
        Route::get('create', CreateSale::class)->name('create');
        Route::get('read/{id}', ReadSale::class)->name('read');
        Route::get('update/{id}', UpdateSale::class)->name('update');
        Route::delete('{id}', [IndexSale::class, 'delete'])->name('delete');
    });
    // Purchases
    Route::prefix('purchases')->name('purchases.')->group(function() {
        Route::get('/', IndexPurchase::class)->name('index');
        Route::get('create', CreatePurchase::class)->name('create');
        Route::get('read/{id}', ReadPurchase::class)->name('read');
        Route::get('update/{id}', UpdatePurchase::class)->name('update');
        Route::delete('{id}', [IndexPurchase::class, 'delete'])->name('delete');
    });
    // Reports


    // Route::prefix('')->name('.')->group(function() {
    //     Route::get('/', ::class)->name('index');
    //     Route::get('create', ::class)->name('create');
    //     Route::get('read/{id}', ::class)->name('read');
    //     Route::get('update/{id}', ::class)->name('update');
    //     Route::delete('{id}', [::class, 'delete'])->name('delete');
    // });

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
