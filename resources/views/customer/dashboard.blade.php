<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('cdn')
    {{-- CSS --}}
        <link href="{{ asset('/css/customerDashboard.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/sideBar.css') }}" rel="stylesheet">
        <link rel="shortcut icon" href="{{ URL('/img/icon.png')}}" type="image/x-icon">
    {{-- CSS --}}
    <title>HOSS</title>
</head>
<body>

    <div class="d-flex" id="wrapper">
        {{-- SIDE NAV --}}
            @include('layouts.customerSidebar')
        {{-- SIDE NAV --}}

        {{-- MAIN CONTENT --}}
            <div id="page-content-wrapper">
                {{-- NAV BAR --}}
                    <nav class="navbar navbar-expand-lg text-white border-bottom">
                        <div class="container-fluid">
                            <button class="btn btn-lg" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                            <h4 class="ms-2 pt-2">CUSTOMER DASHBOARD</h4>
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
                {{-- NAV BAR --}}

                {{-- MAIN CONTENT --}}
                    <div class="container-fluid mainBar">
                        <div class="row my-3">
                            <div class="col-lg-3 col-sm-12 mb-2">
                                <div class="card shadow" style="height:8rem; border-radius:10px; background-color:#ffff;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3 text-center">
                                                <i class="fa-solid fa-ship icons"></i>
                                            </div>
                                            <div class="col-9 text-center" style="line-height:19px; padding-top:1.5rem">
                                                <p class="card-text fw-bold" style="font-size: 2rem; color:#303030;" id="totalPendingReservation"></p>
                                                <p class="card-text fw-bold" style="font-size: 13px; color:#303030;">PENDING RESERVATION</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-12 mb-2">
                                <div class="card shadow" style="height:8rem; border-radius:10px; background-color:#ffff;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3 text-center ">
                                                <i class="fa-solid fa-inbox icons"></i>
                                            </div>
                                            <div class="col-9 text-center" style="line-height:19px; padding-top:1.4rem">
                                                <p class="card-text fw-bold" style="font-size: 2rem; color:#303030;" id="totalCancelReservation"></p>
                                                <p class="card-text fw-bold" style="font-size: 13px; color:#303030;">CANCELLED RESERVATION</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-12 mb-2">
                                <div class="card res shadow" style="height:8rem; border-radius:10px; background-color:#ffff;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3 text-center ">
                                                <i class="fa-solid fa-calendar-days icons"></i>
                                            </div>
                                            <div class="col-9 text-center" style="line-height:19px; padding-top:1.4rem">
                                                <p class="card-text fw-bold" style="font-size: 2rem; color:#303030;" id="totalUnpaidReservation"></p>
                                                <p class="card-text fw-bold" style="font-size: 13px; color:#303030;">UNPAID RESERVATION</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-12 mb-2">
                                <div class="card shadow" style="height:8rem; border-radius:10px; background-color:#ffff;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3 text-center ">
                                                <i class="fa-regular fa-square-check icons"></i>
                                            </div>
                                            <div class="col-9 text-center" style="line-height:19px; padding-top:1.4rem">
                                                <p class="card-text fw-bold" style="font-size: 2rem; color:#303030;" id="totalCompleteReservation"></p>
                                                <p class="card-text fw-bold" style="font-size: 13px; color:#303030;">COMPLETED RESERVATION</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 mt-2 border-2 shadow">
                            <div class="card p-lg-4 p-0 rounded-0"></canvas></div>
                        </div>
                    </div>
                {{-- MAIN CONTENT --}}
            </div>
        {{-- END MAIN CONTENT --}}
    </div>

    {{-- JS --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="{{ asset('/js/dateTime.js') }}"></script>
        <script src="{{ asset('/js/logout.js') }}"></script>
        <script>
            $(document).ready(function(){
                totalPendingReservation();
                totalUnpaidReservation();
                totalCompleteReservation();
                totalCancelReservation();
                graph();
            });

            function totalPendingReservation(){
                $.ajax({
                    url: '/totalPendingReservation',
                    method: 'GET',
                    success : function(data) {
                        $("#totalPendingReservation").html(data);
                    }
                })
            }

            function totalUnpaidReservation(){
                $.ajax({
                    url: '/totalUnpaidReservation',
                    method: 'GET',
                    success : function(data) {
                        $("#totalUnpaidReservation").html(data);
                    }
                })
            }

            function totalCancelReservation(){
                $.ajax({
                    url: '/totalCancelReservation',
                    method: 'GET',
                    success : function(data) {
                        $("#totalCancelReservation").html(data);
                    }
                })
            }

            function totalCompleteReservation(){
                $.ajax({
                    url: '/totalCompleteReservation',
                    method: 'GET',
                    success : function(data) {
                        $("#totalCompleteReservation").html(data);
                    }
                })
            }
        </script>
    {{-- END JS --}}
</body>
</html>
