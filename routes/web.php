<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home;
use App\Http\Controllers\Authentication;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Customer;

// LANDING PAGE
    Route::get('/', [Home::class,'harborView'])->name('harborView');
    Route::get('/harborView', [Home::class,'harborView'])->name('harborView');
// LANDING PAGE

// AUTHENTICATION
    Route::get('/login', [Authentication::class,'login'])->name('login');
    Route::get('/registration', [Authentication::class,'registration'])->name('registration');


    Route::post('registrationFunction', [Authentication::class,'registrationFunction'])->name('registrationFunction');
    Route::post('userLoginFunction', [Authentication::class,'userLoginFunction'])->name('userLoginFunction');
// AUTHENTICATION

// ADMIN DASHBOARD
    Route::get('/adminDashboardRoutes', [Admin::class,'adminDashboardRoutes'])->name('adminDashboardRoutes');
    // ADMIN DASHBOARD
    
// CUSTOMER DASHBOARD
    Route::get('/customerDashboardRoutes', [Customer::class,'customerDashboardRoutes'])->name('customerDashboardRoutes');
// CUSTOMER DASHBOARD







