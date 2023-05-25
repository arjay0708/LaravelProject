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
        Route::get('/adminAcceptReservation', [Admin::class,'adminAcceptReservation'])->name('adminAcceptReservation');
        Route::get('/adminOnGoingReservation', [Admin::class,'adminOnGoingReservation'])->name('adminOnGoingReservation');
        Route::get('/adminDeclineReservation', [Admin::class,'adminDeclineReservation'])->name('adminDeclineReservation');
        Route::get('/adminBackOutReservation', [Admin::class,'adminBackOutReservation'])->name('adminBackOutReservation');
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
        Route::post('/addRoom', [Admin::class,'addRoom'])->name('addRoom');
        Route::get('/getAllPendingReservation', [Admin::class,'getAllPendingReservation'])->name('getAllPendingReservation');
        Route::get('/getAllAcceptReservation', [Admin::class,'getAllAcceptReservation'])->name('getAllAcceptReservation');
        Route::get('/getAllDeclineReservation', [Admin::class,'getAllDeclineReservation'])->name('getAllDeclineReservation');
        Route::get('/getAllBackOutReservation', [Admin::class,'getAllBackOutReservation'])->name('getAllBackOutReservation');
        Route::get('/getAllOnGoingReservation', [Admin::class,'getAllOnGoingReservation'])->name('getAllOnGoingReservation');
        Route::get('/getAllCompletedReservation', [Admin::class,'getAllCompletedReservation'])->name('getAllCompletedReservation');
        Route::get('/acceptReservation', [Admin::class,'acceptReservation'])->name('acceptReservation');
        Route::get('/declineReservation', [Admin::class,'declineReservation'])->name('declineReservation');
        Route::get('/ongoingReservation', [Admin::class,'ongoingReservation'])->name('ongoingReservation');
        Route::get('/completeReservation', [Admin::class,'completeReservation'])->name('completeReservation');
        Route::get('/backOutReservation', [Admin::class,'backOutReservation'])->name('backOutReservation');
        Route::get('/totalPendingReservation', [Admin::class,'totalPendingReservation'])->name('totalPendingReservation');
        Route::get('/totalOnGoingReservation', [Admin::class,'totalOnGoingReservation'])->name('totalOnGoingReservation');
        Route::get('/totalCompletedReservation', [Admin::class,'totalCompletedReservation'])->name('totalCompletedReservation');
        Route::get('/totalCustomer', [Admin::class,'totalCustomer'])->name('totalCustomer');
    // FUNCTION
// ADMIN DASHBOARD
    
// CUSTOMER DASHBOARD
    // ROUTES
        Route::get('/customerDashboard', [Customer::class,'customerDashboard'])->name('customerDashboard');
        Route::get('/customerRoom', [Customer::class,'customerRoom'])->name('customerRoom');
        Route::get('/customerReservation', [Customer::class,'customerReservation'])->name('customerReservation');
        Route::get('/customerAcceptReservation', [Customer::class,'customerAcceptReservation'])->name('customerAcceptReservation');
        Route::get('/customerDeclinedReservation', [Customer::class,'customerDeclinedReservation'])->name('customerDeclinedReservation');
        Route::get('/customerCompleted', [Customer::class,'customerCompleted'])->name('customerReservation');
        Route::get('/customerAccount', [Customer::class,'customerAccount'])->name('customerReservation');
    // ROUTES

    // FUNCTION 
        Route::get('/getCustomerRoom', [Customer::class,'getCustomerRoom'])->name('getCustomerRoom');
        Route::post('/bookReservation', [Customer::class,'bookReservation'])->name('bookReservation');
        Route::get('/cancelReservation', [Customer::class,'cancelReservation'])->name('cancelReservation');
        Route::get('/getBookPerUser', [Customer::class,'getBookPerUser'])->name('getBookPerUser');
        Route::get('/getAcceptBookPerUser', [Customer::class,'getAcceptBookPerUser'])->name('getAcceptBookPerUser');
        Route::get('/getDeclineBookPerUser', [Customer::class,'getDeclineBookPerUser'])->name('getDeclineBookPerUser');
        Route::get('/getCompleteBookPerUser', [Customer::class,'getCompleteBookPerUser'])->name('getCompleteBookPerUser');
        Route::get('/totalPendingReservation', [Customer::class,'totalPendingReservation'])->name('totalPendingReservation');
        Route::get('/totalAcceptReservation', [Customer::class,'totalAcceptReservation'])->name('totalAcceptReservation');
        Route::get('/totalDeclineReservation', [Customer::class,'totalDeclineReservation'])->name('totalDeclineReservation');
        Route::get('/totalCompleteReservation', [Customer::class,'totalCompleteReservation'])->name('totalCompleteReservation');
        Route::get('/getBackOutContent', [Customer::class,'getBackOutContent'])->name('getBackOutContent');
        Route::get('/archivedCancelledReservation', [Customer::class,'archivedCancelledReservation'])->name('archivedCancelledReservation');
        Route::get('/backOutReservation', [Customer::class,'backOutReservation'])->name('backOutReservation');
    // FUNCTION
// CUSTOMER DASHBOARD







