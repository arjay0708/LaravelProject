<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>HarborView Beach Resort</title>
        <!-- CSS -->
            <link rel="shortcut icon" href="{{ URL('/img/whitelogo.png')}}" type="image/x-icon">
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
                            <h4 class="ms-2"> COMPLETED TRANSACTION</h4>
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
                       
                    </div>
                <!-- MAIN CONTENT -->
            </div>
        <!-- MAIN CONTENT -->
    </div>

        <!-- JS -->
            {{-- <script src="{{ asset('/js/administrator/dashboard.js') }}"></script> --}}
            <script src="{{ asset('/js/dateTime.js') }}"></script>
            <script src="{{ asset('/js/logout.js') }}"></script>
        <!-- JS -->
</body>
</html>