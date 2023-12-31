<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>HOSS</title>
        <!-- CSS -->
            <link rel="shortcut icon" href="{{ URL('/img/icon.png')}}" type="image/x-icon">
            <link href="{{ asset('/css/adminDashboard.css') }}" rel="stylesheet">
        <!-- CSS -->
    @include('cdn')
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- SIDE NAV -->
            @include('layouts.adminSidebar')
        <!-- SIDE NAV -->

        <!-- MAIN CONTENT -->
            <div id="page-content-wrapper">
                <!-- NAV BAR -->
                    <nav class="navbar navbar-expand-lg border-bottom">
                        <div class="container-fluid">
                            <h4 class="ms-2"> DASHBOARD</h4>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                                    <li>
                                        <a class="nav-link me-3">
                                            <span>{{ auth()->guard('userModel')->user()->firstname}}</span>
                                            <span>{{ auth()->guard('userModel')->user()->lastname}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                <!-- NAV BAR -->

                <!-- MAIN CONTENT -->
                    <div class="container-fluid mainBar">
                        <div class="row mb-3">
                            <div class="col-3">
                                <div class="card shadow" style="height:8rem; border-radius:10px; background-color:#ffff;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3 text-center">
                                                <i class="bi bi-briefcase"></i>
                                            </div>
                                            <div class="col-9 text-center" style="line-height:19px; padding-top:1.5rem">
                                                <p class="card-text fw-bold" style="font-size: 2rem; color:#303030;" id="totalPendingReservation"></p>
                                                <p class="card-text fw-bold" style="font-size: 13px; color:#303030;">PENDING RESERVATION</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card shadow" style="height:8rem; border-radius:10px; background-color:#ffff;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3 text-start">
                                                <i class="bi bi-calendar-check"></i>
                                            </div>
                                            <div class="col-9 text-start" style="line-height:19px; padding-top:1.5rem">
                                                <p class="card-text fw-bold text-center pe-4" style="font-size: 2rem; color:#303030;" id="totalOnGoingReservation"></p>
                                                <p class="card-text fw-bold" style="font-size: 12px; color:#303030;">ON-GOING RESERVATION</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card shadow" style="height:8rem; border-radius:10px; background-color:#ffff;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3 text-start">
                                                <i class="bi bi-person-workspace"></i>
                                            </div>
                                            <div class="col-9 text-center" style="line-height:19px; padding-top:1.5rem">
                                                <p class="card-text fw-bold" style="font-size: 2rem; color:#303030;" id="totalCompletedReservation"></p>
                                                <p class="card-text fw-bold" style="font-size: 12px; color:#303030;">COMPLETED RESERVATION</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card shadow" style="height:8rem; border-radius:10px; background-color:#ffff;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3 text-start">
                                                <i class="bi bi-people-fill"></i>
                                            </div>
                                            <div class="col-9 text-center" style="line-height:19px; padding-top:1.5rem">
                                                <p class="card-text fw-bold pe-2" style="font-size: 2rem; color:#303030;" id="totalCustomer"></p>
                                                <p class="card-text fw-bold" style="font-size: 13px; letter-spacing:1px; color:#303030;">TOTAL CUSTOMER</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 mt-2 border-2 shadow">
                            <div class="card p-lg-4 p-0 rounded-0" id="fetchAllBackOut"></div>
                        </div>
                    </div>
                <!-- MAIN CONTENT -->
            </div>
        <!-- MAIN CONTENT -->
    </div>

        <!-- JS -->
            <script src="{{ asset('/js/admin/dashboard.js') }}"></script>
            <script src="{{ asset('/js/dateTime.js') }}"></script>
            <script src="{{ asset('/js/logout.js') }}"></script>
        <!-- JS -->
</body>
</html>
