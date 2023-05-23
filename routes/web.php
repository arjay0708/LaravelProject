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
    Route::get('/logoutFunction', [Authentication::class,'logoutFunction'])->name('logoutFunction');
// AUTHENTICATION

// ADMIN DASHBOARD

    // ROUTES
        Route::get('/adminDashboard', [Admin::class,'adminDashboard'])->name('adminDashboard');
        Route::get('/adminRoom', [Admin::class,'adminRoom'])->name('adminRoom');
        Route::get('/adminNotAvailableRoom', [Admin::class,'adminNotAvailableRoom'])->name('adminNotAvailableRoom');
        Route::get('/addNewRoom', [Admin::class,'addNewRoom'])->name('addNewRoom');
        Route::get('/adminReservation', [Admin::class,'adminReservation'])->name('adminReservation');
        Route::get('/adminCompleted', [Admin::class,'adminCompleted'])->name('adminCompleted');
        Route::get('/adminCustomer', [Admin::class,'adminCustomer'])->name('adminCustomer');
        Route::get('/adminInActiveCustomer', [Admin::class,'adminInActiveCustomer'])->name('adminInActiveCustomer');
        Route::get('/adminAccount', [Admin::class,'adminAccount'])->name('adminAccount');
    // ROUTES

    // FUNCTION 
        Route::get('/getActiveCustomer', [Admin::class,'getActiveCustomer'])->name('getActiveCustomer');
        Route::get('/getInActiveCustomer', [Admin::class,'getInActiveCustomer'])->name('getInActiveCustomer');
        Route::get('/getUserInfo', [Admin::class,'getUserInfo'])->name('getUserInfo');
        Route::get('/getAvailableRoom', [Admin::class,'getAvailableRoom'])->name('getAvailableRoom');
        Route::get('/getNotAvailableRoom', [Admin::class,'getNotAvailableRoom'])->name('getNotAvailableRoom');
    // FUNCTION
// ADMIN DASHBOARD
    
// CUSTOMER DASHBOARD
    Route::get('/customerDashboardRoutes', [Customer::class,'customerDashboardRoutes'])->name('customerDashboardRoutes');
// CUSTOMER DASHBOARD







