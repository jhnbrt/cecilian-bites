<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Cashier\DashboardController;
use App\Http\Controllers\Cashier\OrderController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::middleware('role:cashier,admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::patch('foods/{food}/availability', [Admin\FoodController::class, 'toggleAvailability'])->name('foods.availability');
        Route::resource('foods', Admin\FoodController::class)->except('show');

        Route::resource('categories', Admin\CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('tables', Admin\TableController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::get('orders', [Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');

        Route::get('reports', [Admin\ReportController::class, 'index'])->name('reports.index');

        Route::resource('staff', Admin\StaffController::class)->except('show')->parameters(['staff' => 'user']);
    });
});
