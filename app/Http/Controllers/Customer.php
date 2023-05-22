<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Customer extends Controller
{
    public function customerDashboardRoutes(){
        return view('customer/dashboard');
    }   
}
