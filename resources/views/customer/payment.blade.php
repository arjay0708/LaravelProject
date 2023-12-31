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
    <title>H0SS</title>
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
                            <h4 class="ms-2 pt-2">Payment</h4>
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
                    @foreach ($data as $item)
                    <div class="container-fluid mainBar">
                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-3">
                                    <div class="row g-0">
                                      <div class="col-md-5">
                                        <img src="{{ $item->photos }}" class="img-fluid rounded-start" style="height: 100%">
                                      </div>
                                      <div class="col-md-7">
                                        <div class="card-body">
                                          <ul class="list-group list-group-flush">
                                            <li class="list-group-item fw-bold"><h5 class="card-title">Reservation Code: <span class="fw-normal"> {{ $item->book_code }}</span> </h5> </li>
                                            <li class="list-group-item fw-bold">Room Number: <span class="fw-normal"> {{ $item->room_number }}</span> </li>
                                            <li class="list-group-item fw-bold">Floor: <span class="fw-normal"> {{ $item->floor }}</span> </li>
                                            <li class="list-group-item fw-bold">Type of Room: <span class="fw-normal"> {{ $item->type_of_room }}</span> </li>
                                            <li class="list-group-item fw-bold">Number of Bed: <span class="fw-normal"> {{ $item->number_of_bed	 }}</span> </li>
                                            <li class="list-group-item fw-bold">Price Per Night: <span class="fw-normal"> ₱{{ $item->price_per_hour}}.00</span> </li>
                                            <li class="list-group-item fw-bold">Details: <span class="fw-normal"> {{ $item->details }}</span> </li>
                                            <li class="list-group-item fw-bold">Check In: <span class="fw-normal">{{\Carbon\Carbon::parse($item->start_dataTime)->format('F d, Y - 02:00 A') }}</span></li>
                                            <li class="list-group-item fw-bold">Check Out: <span class="fw-normal">{{\Carbon\Carbon::parse($item->end_dateTime)->format('F d, Y - 12:00 A') }}</span></li>
                                            <li class="list-group-item fw-bold">Total Night(s):<span class="fw-normal"> {{ ceil(\Carbon\Carbon::parse($item->start_dataTime)->diffInHours(\Carbon\Carbon::parse($item->end_dateTime)) / 24) }} Night(s)</span></li>
                                            <li class="list-group-item fw-bold">Total Payment: <span class="fw-normal">₱{{ $item->price_per_hour * ceil(\Carbon\Carbon::parse($item->start_dataTime)->diffInHours(\Carbon\Carbon::parse($item->end_dateTime)) / 24) }}.00</span></li>
                                            <li class="list-group-item fw-bold">Advance Payment: <span class="fw-normal">₱{{ ($item->price_per_hour * ceil(\Carbon\Carbon::parse($item->start_dataTime)->diffInHours(\Carbon\Carbon::parse($item->end_dateTime)) / 24)) / 2 }}.00</span></li>
                                            <p class="card-text pt-3 ms-3 fw-bold" >Notes: To proceed with securing this reservation, you are required to make the necessary payment.</p>
                                          </ul>
                                        </div>
                                        <div class="card-footer bg-white text-end"><button type='button' class='btn btn-sm btn-primary px-4 py-2 rounded-0'>Continue to Pay</button></div>
                                      </div>
                                    </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                {{-- MAIN CONTENT --}}
            </div>
        {{-- END MAIN CONTENT --}}
    </div>

    {{-- JS --}}
        <script src="{{ asset('/js/customer/reservation.js') }}"></script>
        <script src="{{ asset('/js/dateTime.js') }}"></script>
        <script src="{{ asset('/js/logout.js') }}"></script>
    {{-- END JS --}}
</body>
</html>
