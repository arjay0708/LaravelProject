<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\userModel;
use App\Models\roomModel;
use App\Models\reservationModel;
use App\Models\reasonBackOutModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class Customer extends Controller
{
    // ROUTES
        public function customerDashboard()
        {
            return view('customer/dashboard');
        }
        public function customerRoom()
        {
            return view('customer/room');
        }
        public function customerReservation()
        {
            return view('customer/reservation');
        }
        public function customerAcceptReservation()
        {
            return view('customer/acceptReservation');
        }
        public function customerCancelReservation()
        {
            return view('customer/cancelReservation');
        }
        public function customerDeclinedReservation()
        {
            return view('customer/declinedReservation');
        }
        public function customerUnpaidReservation()
        {
            return view('customer/unpaidReservation');
        }
        public function customerCompleted()
        {
            return view('customer/complete');
        }
        public function customerAccount()
        {
            return view('customer/account');
        }
        public function customerCredentials()
        {
            return view('customer/credentials');
        }
    // ROUTES

    // FUNCTION

    // SHOW ROOM FOR CUSTOMER
    public function getCustomerRoom(Request $request)
    {
        $data = roomModel::where([['is_available', '!=', 0]])->orderBy('room_id')->get();
        if ($data->isNotEmpty()) {
            foreach ($data as $item) {
                echo "
                                <div class='col-lg-6 col-sm-12 g-0 gx-lg-5 text-center text-lg-start'>
                                    <div class='card mb-3 shadow border-2 border rounded' style='width:100%'>
                                        <div class='row g-0'>
                                            <img loading='lazy' src=$item->photos class='card-img-top img-thumdnail' style='height:230px; width:100%;' alt='ship'>
                                            <div class='col-md-12'>
                                                <ul class='list-group list-group-flush fw-bold'>
                                                    <li class='list-group-item'>
                                                        <div class='row'>
                                                            <div class='col-12 col-lg-6 ps-0 ps-lg-4'>
                                                                Room Number: <span class='fw-normal'> $item->room_number</span>
                                                            </div>
                                                            <div class='col-12 col-lg-6 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                                Room Floor:<span class='fw-normal'> $item->floor</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class='list-group-item'>
                                                        <div class='row'>
                                                            <div class='col-12 col-lg-6 ps-0 ps-lg-4'>
                                                                Type of Room: <span class='fw-normal'>$item->type_of_room</span>
                                                            </div>
                                                            <div class='col-12 col-lg-6 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                                Number of Bed:<span class='fw-normal'> $item->number_of_bed Only</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class='list-group-item'>
                                                        <div class='row'>
                                                            <div class='col-12 col-lg-6 ps-0 ps-lg-4'>
                                                                Max Person: <span class='fw-normal'>$item->max_person People Only</span>
                                                            </div>
                                                            <div class='col-12 col-lg-6 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                                Price Per Night(s): <span class='fw-normal'> ₱$item->price_per_hour.00</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class='list-group-item fw-bold' style='color:#'>
                                                        <div class='col-12'>
                                                            Details: <span class='fw-normal'>$item->details</span>
                                                        </div>
                                                    </li>
                                                    <li class='list-group-item text-center text-lg-end py-2'>
                                                        <button onclick='bookReservation($item->room_id)' type='button' class='btn btn-sm btn-dark px-4 py-2 rounded-0'>BOOK NOW</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ";
            }
        } else {
            echo "
                        <div class='row applicantNoSchedule' style='margin-top:20rem; color: #8d8a85;'>
                            <div class='alert alert-light text-center fs-4' role='alert' style='color: #8d8a85;'>
                                NO ROOM AVAILABLE
                            </div>
                        </div>
                        ";
        }
    }

    // BOOK RESERVATION
    public function bookReservation(Request $request)
    {
        $checkInDateTime = Carbon::parse($request->checkInDate . '14:00:00');
        $formattedCheckIn = $checkInDateTime->format('Y-m-d H:i:s');
        $checkOutDateTime = Carbon::parse($request->checkOutDate . '12:00:00');
        $formattedCheckOut = $checkOutDateTime->format('Y-m-d H:i:s');

        $currentDateTime = now()->format('Y-m-d H:i:s');
        $random = Carbon::now()->format('YmdHis') . rand(001, 999);

        $user = auth()->guard('userModel')->user();

        if (empty($user->lastname) || empty($user->firstname)) {
            return response()->json(5);
        }

        $existingReservation = ReservationModel::where('room_id', $request->roomId)
            ->where('status', 'Pending')
            ->where(function ($query) use ($user, $formattedCheckIn, $formattedCheckOut) {
                $query->where(function ($query) use ($formattedCheckIn, $formattedCheckOut) {
                    $query->where(function ($query) use ($formattedCheckIn, $formattedCheckOut) {
                        $query->where('start_dataTime', '<', $formattedCheckOut)
                            ->where('end_dateTime', '>', $formattedCheckIn);
                    })
                        ->orWhere(function ($query) use ($formattedCheckIn, $formattedCheckOut) {
                            $query->where('start_dataTime', $formattedCheckIn)
                                ->where('end_dateTime', $formattedCheckOut);
                        });
                });
            })
            ->exists();

        if ($existingReservation) {
            return response()->json(6);
        }

        if ($currentDateTime > $formattedCheckIn) {
            return response()->json(4);
        } elseif ($formattedCheckIn == $formattedCheckOut) {
            return response()->json(2);
        } elseif ($formattedCheckOut < $formattedCheckIn) {
            return response()->json(3);
        }

        $bookRoom = ReservationModel::create([
            'book_code' => $random,
            'user_id' => $user->user_id,
            'room_id' => $request->roomId,
            'start_dataTime' => $formattedCheckIn,
            'end_dateTime' => $formattedCheckOut,
            'status' => 'Unpaid',
            'is_archived' => 0,
            'is_noted' => 0
        ]);

        if ($bookRoom) {
            return response()->json(['status' => 1, 'book_code' => $bookRoom->book_code]);
        } else {
            return response()->json(['status' => 0]);
        }
    }

    public function payment($book_code)
    {
        $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
            ->where([['book_code', '=', $book_code]])->select(
                'roomTable.room_id',
                'roomTable.photos',
                'roomTable.room_number',
                'roomTable.floor',
                'roomTable.type_of_room',
                'roomTable.number_of_bed',
                'roomTable.details',
                'roomTable.price_per_hour',
                'reservationTable.reservation_id',
                'reservationTable.book_code',
                'reservationTable.start_dataTime',
                'reservationTable.end_dateTime'
            )->get();
        return view('customer/payment', compact('data'));
    }

    // PENDING RESERVATION PER USER
    public function getBookPerUser(Request $request)
    {
        $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
            ->where(
                [['reservationTable.status', '=', 'Pending'], ['reservationTable.user_id', '=', auth()->guard('userModel')->user()->user_id]]
            )->orderBy('reservationTable.reservation_id', 'ASC')->get();
        if ($data->isNotEmpty()) {
            foreach ($data as $item) {
                // CALCULATE OF TOTAL HOURS
                $checkInDateTime = date('F d, Y', strtotime($item->start_dataTime));
                $checkOutDateTime = date('F d, Y', strtotime($item->end_dateTime));

                $carbonStart = Carbon::parse($checkInDateTime);
                $carbonEnd = Carbon::parse($checkOutDateTime);

                $totalNights = ceil($carbonStart->diffInHours($carbonEnd) / 24);

                $totalPayment = $totalNights * $item->price_per_hour;
                echo "
                                <div class='col-lg-6 col-sm-12 g-0 gx-lg-5 text-center text-lg-start'>
                                    <div class='card mb-3 shadow border-2 border rounded' style='width:100%'>
                                        <div class='row g-0'>
                                            <img loading='lazy' src=$item->photos class='card-img-top img-thumdnail' style='height:230px; width:100%;' alt='ship'>
                                            <div class='col-md-12'>
                                                <ul class='list-group list-group-flush fw-bold'>
                                                    <li class='list-group-item'>
                                                        <div class='row'>
                                                            <div class='col-12 col-lg-6 ps-0 ps-lg-4'>
                                                                Room Number: <span class='fw-normal'> $item->room_number</span>
                                                            </div>
                                                            <div class='col-12 col-lg-6 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                                Room Floor:<span class='fw-normal'> $item->floor</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class='list-group-item'>
                                                        <div class='row'>
                                                            <div class='col-12 col-lg-6 ps-0 ps-lg-4'>
                                                                Type of Room: <span class='fw-normal'>$item->type_of_room</span>
                                                            </div>
                                                            <div class='col-12 col-lg-6 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                                Number of Bed:<span class='fw-normal'> $item->number_of_bed Only</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class='list-group-item'>
                                                        <div class='row'>
                                                            <div class='col-12 col-lg-6 ps-0 ps-lg-4'>
                                                                Max Person: <span class='fw-normal'>$item->max_person People Only</span>
                                                            </div>
                                                            <div class='col-12 col-lg-6 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                                Price Per Night(s): <span class='fw-normal'> ₱$item->price_per_hour.00</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class='list-group-item fw-bold' style='color:#'>
                                                        <div class='col-12'>
                                                            Details: <span class='fw-normal'>$item->details</span>
                                                        </div>
                                                    </li>
                                                    <li class='list-group-item'>
                                                        <div class='row'>
                                                            <div class='col-12 col-lg-7 ps-0 ps-lg-4'>
                                                                Check In: <span class='fw-normal'> $checkInDateTime - 02:00 PM</span><br>
                                                                Check Out:<span class='fw-normal'> $checkOutDateTime - 12:00 PM</span>
                                                            </div>
                                                            <div class='col-12 col-lg-5 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                                Total Night(s): <span class='fw-normal'> $totalNights</span><br>
                                                                Total Payment:<span class='fw-normal'> ₱$totalPayment.00</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class='list-group-item text-center text-lg-end py-2'>
                                                        <button onclick='cancelReservation($item->reservation_id)' type='button' class='btn btn-sm btn-danger px-4 py-2 rounded-0'>CANCEL BOOK</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ";
            }
        } else {
            echo "
                        <div class='row applicantNoSchedule' style='margin-top:20rem; color: #8d8a85;'>
                            <div class='alert alert-light text-center fs-4' role='alert' style='color: #8d8a85;'>
                                NO RESERVATION FOUND
                            </div>
                        </div>
                        ";
        }
    }

    // CANCEL RESERVATION PER USER
    public function getCancelBookPerUser(Request $request)
    {
        $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
            ->join('reasonBackOutTable', 'reservationTable.reservation_id', '=', 'reasonBackOutTable.reservation_id')
            ->where(
                [['reservationTable.status', '=', 'Cancel'], ['reservationTable.user_id', '=', auth()->guard('userModel')->user()->user_id]]
            )->orderBy('reservationTable.reservation_id', 'ASC')
            ->select(
                'roomTable.room_id',
                'roomTable.photos',
                'roomTable.room_number',
                'roomTable.floor',
                'roomTable.type_of_room',
                'roomTable.number_of_bed',
                'roomTable.details',
                'roomTable.max_person',
                'roomTable.price_per_hour',
                'reservationTable.book_code',
                'reservationTable.start_dataTime',
                'reservationTable.end_dateTime',
                'reasonBackOutTable.reason',
                'reasonBackOutTable.updated_at',
            )
            ->orderBy('reasonBackOutTable.updated_at', 'ASC')->get();
        if ($data->isNotEmpty()) {
            foreach ($data as $item) {
                $currentDateTime = Carbon::now()->format('F d, Y g:i A');

                $checkInDateTime = date('F d, Y', strtotime($item->start_dataTime));
                $checkOutDateTime = date('F d, Y', strtotime($item->end_dateTime));
                $cancelDateTime = date('F d, Y', strtotime($item->updated_at));

                $carbonStart = Carbon::parse($checkInDateTime);
                $carbonEnd = Carbon::parse($checkOutDateTime);

                $totalNights = ceil($carbonStart->diffInHours($carbonEnd) / 24);

                $totalPayment = $totalNights * $item->price_per_hour;
                echo "
                        <div class='col-lg-6 col-sm-12 g-0 gx-lg-5 text-center text-lg-start'>
                            <div class='card mb-3 shadow border-2 border rounded' style='width:100%'>
                                    <div class='row g-0'>
                                        <img loading='lazy' src=$item->photos class='card-img-top img-thumdnail' style='height:230px; width:100%;' alt='ship'>
                                        <div class='col-md-12'>
                                            <ul class='list-group list-group-flush fw-bold'>
                                                <li class='list-group-item'>
                                                    <div class='row'>
                                                        <div class='col-12 col-lg-6 ps-0 ps-lg-4'>
                                                            Room Number: <span class='fw-normal'> $item->room_number</span>
                                                        </div>
                                                        <div class='col-12 col-lg-6 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                            Room Floor:<span class='fw-normal'> $item->floor</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class='list-group-item'>
                                                    <div class='row'>
                                                        <div class='col-12 col-lg-6 ps-0 ps-lg-4'>
                                                            Type of Room: <span class='fw-normal'>$item->type_of_room</span>
                                                        </div>
                                                        <div class='col-12 col-lg-6 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                            Number of Bed:<span class='fw-normal'> $item->number_of_bed</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class='list-group-item'>
                                                    <div class='row'>
                                                        <div class='col-12 col-lg-6 ps-0 ps-lg-4'>
                                                            Max Person: <span class='fw-normal'>$item->max_person People</span>
                                                        </div>
                                                        <div class='col-12 col-lg-6 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                            Price Per Night(s): <span class='fw-normal'> ₱$item->price_per_hour.00</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class='list-group-item fw-bold' style='color:#'>
                                                    <div class='col-12'>
                                                        Details: <span class='fw-normal'>$item->details</span>
                                                    </div>
                                                </li>
                                                <li class='list-group-item'>
                                                    <div class='row'>
                                                        <div class='col-12 col-lg-7 ps-0 ps-lg-4'>
                                                            Check In: <span class='fw-normal'> $checkInDateTime - 02:00 PM</span><br>
                                                            Check Out:<span class='fw-normal'> $checkOutDateTime - 12:00 PM</span>
                                                        </div>
                                                        <div class='col-12 col-lg-5 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                            Total Night(s): <span class='fw-normal'> $totalNights</span><br>
                                                            Total Payment:<span class='fw-normal'> ₱$totalPayment.00</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class='list-group-item fw-bold' style='color:#'>
                                                <div class='col-12'>
                                                    Reason: <span class='fw-normal'>$item->reason</span>
                                                </div>
                                                </li>
                                                <li class='list-group-item text-center text-lg-end py-2'>
                                                    <p class='card-text'><small class='text-danger'>Cancel the Reservation Last $cancelDateTime</small></p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ";
            }
        } else {
            echo "
                    <div class='row applicantNoSchedule' style='margin-top:20rem; color: #8d8a85;'>
                        <div class='alert alert-light text-center fs-4' role='alert' style='color: #8d8a85;'>
                            NO RESERVATION FOUND
                        </div>
                    </div>
                ";
        }
    }

    // UNPAID RESERVATION PER USER
    public function getUnpaidBooking(Request $request)
    {
        $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
            ->where(
                [['reservationTable.status', '=', 'Unpaid'], ['reservationTable.user_id', '=', auth()->guard('userModel')->user()->user_id]]
            )->orderBy('reservationTable.reservation_id', 'ASC')->get();
        if ($data->isNotEmpty()) {
            foreach ($data as $item) {
                $currentDateTime = Carbon::now()->format('F d, Y g:i A');

                $checkInDateTime = date('F d, Y', strtotime($item->start_dataTime));
                $checkOutDateTime = date('F d, Y', strtotime($item->end_dateTime));

                $carbonStart = Carbon::parse($checkInDateTime);
                $carbonEnd = Carbon::parse($checkOutDateTime);

                $totalNights = ceil($carbonStart->diffInHours($carbonEnd) / 24);
                $totalPayment = $totalNights * $item->price_per_hour;
                $typeOfRoom = $item->type_of_room;

                echo "
                    <div class='col-lg-6 col-sm-12 g-0 gx-lg-5 text-center text-lg-start'>
                        <div class='card mb-3 shadow border-2 border rounded' style='width:100%'>
                            <div class='row g-0'>
                                <img loading='lazy' src=$item->photos class='card-img-top img-thumdnail' style='height:230px; width:100%;' alt='ship'>
                                <div class='col-md-12'>
                                    <ul class='list-group list-group-flush fw-bold'>
                                        <li class='list-group-item'>
                                            <div class='row'>
                                                <div class='col-12 col-lg-6 ps-0 ps-lg-4'>
                                                    Room Number: <span class='fw-normal'> $item->room_number</span>
                                                </div>
                                                <div class='col-12 col-lg-6 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                    Room Floor:<span class='fw-normal'> $item->floor</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class='list-group-item'>
                                            <div class='row'>
                                                <div class='col-12 col-lg-6 ps-0 ps-lg-4'>
                                                    Type of Room: <span class='fw-normal'>$item->type_of_room</span>
                                                </div>
                                                <div class='col-12 col-lg-6 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                    Number of Bed:<span class='fw-normal'> $item->number_of_bed Only</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class='list-group-item'>
                                            <div class='row'>
                                                <div class='col-12 col-lg-6 ps-0 ps-lg-4'>
                                                    Max Person: <span class='fw-normal'>$item->max_person People Only</span>
                                                </div>
                                                <div class='col-12 col-lg-6 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                    Price Per Night(s): <span class='fw-normal'> ₱$item->price_per_hour.00</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class='list-group-item fw-bold' style='color:#'>
                                            <div class='col-12'>
                                                Details: <span class='fw-normal'>$item->details</span>
                                            </div>
                                        </li>
                                        <li class='list-group-item'>
                                            <div class='row'>
                                                <div class='col-12 col-lg-7 ps-0 ps-lg-4'>
                                                    Check In: <span class='fw-normal'> $checkInDateTime - 02:00 PM</span><br>
                                                    Check Out:<span class='fw-normal'> $checkOutDateTime - 12:00 PM</span>
                                                </div>
                                                <div class='col-12 col-lg-5 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                    Total Night(s): <span class='fw-normal'> $totalNights</span><br>
                                                    Total Payment:<span class='fw-normal'> ₱$totalPayment.00</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class='list-group-item text-center text-lg-end py-2'>
                                            <div class='row mt-3'>
                                                <div class='col-12 col-lg-12 ps-0 ps-lg-4'>
                                                    <span class='fw-normal text-dark'>Notes: To proceed this booking, the payment for the reservation is required. </span><br>
                                                </div>
                                            </div>
                                            <a onclick='deleteReservation($item->reservation_id)' type='button' class='btn btn-sm btn-danger px-3 py-2 rounded-0 mt-2 text-white'>Cancel Booking</a>
                                            <a onclick='getUpdateUnpaidReservation($item->reservation_id)' type='button' class='btn btn-sm btn-secondary px-3 py-2 rounded-0 mt-2 text-white'>Update Booking</a>
                                            <a href='" . route('stripePayment', ['total_payment' => $totalPayment, 'type_of_room' => $typeOfRoom, 'reservation_id' => $item->reservation_id]) . "' type='button' id='continueToPayBtn' class='btn btn-sm btn-primary px-3 py-2 rounded-0 mt-2'>Continue to Pay</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                ";
            }
        } else {
            echo "
                <div class='row applicantNoSchedule' style='margin-top:20rem; color: #8d8a85;'>
                    <div class='alert alert-light text-center fs-4' role='alert' style='color: #8d8a85;'>
                        NO RESERVATION FOUND
                    </div>
                </div>
                ";
        }
    }

    // COMPLETE RESERVATION PER USER
    public function getCompleteBookPerUser(Request $request)
    {
        $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
            ->where(
                [['reservationTable.status', '=', 'Complete'], ['reservationTable.user_id', '=', auth()->guard('userModel')->user()->user_id]]
            )->orderBy('reservationTable.reservation_id', 'ASC')->get();
        if ($data->isNotEmpty()) {
            foreach ($data as $item) {
                // CALCULATE OF TOTAL HOURS
                $checkInDateTime = date('F d, Y', strtotime($item->start_dataTime));
                $checkOutDateTime = date('F d, Y', strtotime($item->end_dateTime));

                $carbonStart = Carbon::parse($checkInDateTime);
                $carbonEnd = Carbon::parse($checkOutDateTime);

                $totalNights = ceil($carbonStart->diffInHours($carbonEnd) / 24);

                $totalPayment = $totalNights * $item->price_per_hour;
                echo "
                                <div class='col-lg-6 col-sm-12 g-0 gx-lg-5 text-center text-lg-start'>
                                    <div class='card mb-3 shadow border-2 border rounded' style='width:100%'>
                                        <div class='row g-0'>
                                            <img loading='lazy' src=$item->photos class='card-img-top img-thumdnail' style='height:230px; width:100%;' alt='ship'>
                                            <div class='col-md-12'>
                                                <ul class='list-group list-group-flush fw-bold'>
                                                    <li class='list-group-item'>
                                                        <div class='row'>
                                                            <div class='col-12 col-lg-6 ps-0 ps-lg-4'>
                                                                Room Number: <span class='fw-normal'> $item->room_number</span>
                                                            </div>
                                                            <div class='col-12 col-lg-6 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                                Room Floor:<span class='fw-normal'> $item->floor</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class='list-group-item'>
                                                        <div class='row'>
                                                            <div class='col-12 col-lg-6 ps-0 ps-lg-4'>
                                                                Type of Room: <span class='fw-normal'>$item->type_of_room</span>
                                                            </div>
                                                            <div class='col-12 col-lg-6 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                                Number of Bed:<span class='fw-normal'> $item->number_of_bed Only</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class='list-group-item'>
                                                        <div class='row'>
                                                            <div class='col-12 col-lg-6 ps-0 ps-lg-4'>
                                                                Max Person: <span class='fw-normal'>$item->max_person People Only</span>
                                                            </div>
                                                            <div class='col-12 col-lg-6 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                                Price Per Night(s): <span class='fw-normal'> ₱$item->price_per_hour.00</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class='list-group-item fw-bold' style='color:#'>
                                                        <div class='col-12'>
                                                            Details: <span class='fw-normal'>$item->details</span>
                                                        </div>
                                                    </li>
                                                    <li class='list-group-item'>
                                                        <div class='row py-2'>
                                                            <div class='col-12 col-lg-7 ps-0 ps-lg-4'>
                                                                Check In: <span class='fw-normal'> $checkInDateTime - 02:00 PM</span><br>
                                                                Check Out:<span class='fw-normal'> $checkOutDateTime - 12:00 PM</span>
                                                            </div>
                                                            <div class='col-12 col-lg-5 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                                Total Hours: <span class='fw-normal'> $totalNights</span><br>
                                                                Total Payment:<span class='fw-normal'> ₱$totalPayment.00</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ";
            }
        } else {
            echo "
                        <div class='row applicantNoSchedule' style='margin-top:20rem; color: #8d8a85;'>
                            <div class='alert alert-light text-center fs-4' role='alert' style='color: #8d8a85;'>
                                NO RESERVATION FOUND
                            </div>
                        </div>
                        ";
        }
    }

    // GET ALL TOTAL
    public function getAllTotalForCustomer(Request $request){
        $pendingReservation = reservationModel::where([['user_id', '=', auth()->guard('userModel')->user()->user_id],['status', '=', 'Pending']])->get();
        $totalPendingReservation = $pendingReservation->count();

        $unpaidReservation = reservationModel::where([['user_id', '=', auth()->guard('userModel')->user()->user_id],['status', '=', 'Unpaid']])->get();
        $totalUnpaidReservation = $unpaidReservation->count();

        $cancelledReservation = reservationModel::where([['user_id', '=', auth()->guard('userModel')->user()->user_id],['status', '=', 'Cancel']])->get();
        $totalCancelReservation = $cancelledReservation->count();

        $completeReservation = reservationModel::where([['user_id', '=', auth()->guard('userModel')->user()->user_id],['status', '=', 'Complete']])->get();
        $totalCompleteReservation = $completeReservation->count();

        return response()->json([
            'totalPendingReservation' => $totalPendingReservation,
            'totalUnpaidReservation' => $totalUnpaidReservation,
            'totalCancelReservation' => $totalCancelReservation,
            'totalCompleteReservation' => $totalCompleteReservation,
        ]);
    }

    // ARCHIVED CANCELLED RESERVATION
    public function archivedCancelledReservation(Request $request)
    {
        $archive = reservationModel::where([['reservation_id', '=', $request->reservationId]])->update(['is_archived' => 1]);
        return response()->json($archive ? 1 : 0);
    }

    // CANCEL THE ACCEPTED RESERVATION
    public function cancelReservation(Request $request)
    {
        $cancelReservation = reservationModel::where([['reservation_id', '=', $request->reservationId]])->update(['status' => 'Cancel']);
        if ($cancelReservation) {
            $backOutReason = reasonBackOutModel::create([
                'reservation_id' => $request->reservationId,
                'user_id' => auth()->guard('userModel')->user()->user_id,
                'reason' => $request->reason,
                'set_by_admin' => 0,
            ]);
            return response()->json($backOutReason ? 1 : 0);
        }
    }

    // DELETE UNPAID RESERVATION
    public function deleteReservation(Request $request)
    {
        $deleteReservation = reservationModel::where([['reservation_id', '=', $request->reservationId]])->delete();
        return response()->json($deleteReservation ? 1 : 0);
    }

    // FETCH ACCOUNT PER USER
    public function getUserInfo(Request $request)
    {
        $data = userModel::where([['user_id', '=', auth()->guard('userModel')->user()->user_id]])->first();
        return response()->json($data);
    }

    // UPDATE ACCOUNT PER USER
    public function updateUserAccount(Request $request)
    {
        $update = userModel::find($request->userUniqueId);
        if ($request->hasFile('userProfile')) {
            $filename = $request->file('userProfile');
            $imageName = time() . rand() . '.' . $filename->getClientOriginalExtension();
            $path = $request->file('userProfile')->storeAs('userPhotos', $imageName, 'public');
            $update->photos = '/storage/' . $path;
        }

        $update->lastname = $request->input('userLastName');
        $update->firstname = $request->input('userFirstName');
        $update->middlename = $request->input('userMiddleName');
        $update->extention = $request->input('userExtension');
        $update->email = $request->input('userEmail');
        $update->phoneNumber = $request->input('userPhoneNumber');
        $update->birthday = $request->input('userBirthday');
        $update->age = $request->input('userAge');
        $update->save();
        return response()->json(1);
    }

    // UPDATE CREDS PER USER
    public function updateUserCredentials(Request $request)
    {
        $user = auth()->guard('userModel')->user();
        $userData = userModel::where('user_id', $user->user_id)->first(['password']);
        if (!Hash::check($request->userOldPassword, $userData->password)) {
            return response()->json(2);
        }
        userModel::where('user_id', $user->user_id)->update(['password' => Hash::make($request->userNewPassword)]);
        Session::flush();
        Auth::guard('userModel')->logout();
        return response()->json(1);
    }

    // UPDATE UNPAID RESERVATION
    public function updateUnpaidReservation(Request $request){
        $checkInDateTime = Carbon::parse($request->checkInDate . '14:00:00');
        $formattedCheckIn = $checkInDateTime->format('Y-m-d H:i:s');
        $checkOutDateTime = Carbon::parse($request->checkOutDate . '12:00:00');
        $formattedCheckOut = $checkOutDateTime->format('Y-m-d H:i:s');

        $currentDateTime = now()->format('Y-m-d H:i:s');
        $random = Carbon::now()->format('YmdHis') . rand(001, 999);

        $user = auth()->guard('userModel')->user();

        if (empty($user->lastname) || empty($user->firstname)) {
            return response()->json(5);
        }

        $existingReservation = ReservationModel::where('room_id', $request->roomId)
            ->where('status', 'Pending')
            ->where(function ($query) use ($user, $formattedCheckIn, $formattedCheckOut) {
                $query->where(function ($query) use ($formattedCheckIn, $formattedCheckOut) {
                    $query->where(function ($query) use ($formattedCheckIn, $formattedCheckOut) {
                        $query->where('start_dataTime', '<', $formattedCheckOut)
                            ->where('end_dateTime', '>', $formattedCheckIn);
                    })
                        ->orWhere(function ($query) use ($formattedCheckIn, $formattedCheckOut) {
                            $query->where('start_dataTime', $formattedCheckIn)
                                ->where('end_dateTime', $formattedCheckOut);
                        });
                });
            })
            ->exists();

        if ($existingReservation) {
            return response()->json(6);
        }
        if ($currentDateTime > $formattedCheckIn) {
            return response()->json(4);
        } elseif ($formattedCheckIn == $formattedCheckOut) {
            return response()->json(2);
        } elseif ($formattedCheckOut < $formattedCheckIn) {
            return response()->json(3);
        }

        $update = ReservationModel::find($request->reservationId);
        $update->start_dataTime = $formattedCheckIn; // Corrected property name
        $update->end_dateTime = $formattedCheckOut;
        $update->save();
        return response()->json(1);
    }

    // VIEW UNPAID RESERVATION
    public function viewUnpaidReservation(Request $request){
        $data = ReservationModel::where([['reservation_id', '=', $request->reservationId]])->select('reservation_id','start_dataTime', 'end_dateTime')->first();
        return response()->json($data);
    }

    // FUNCTION
}
