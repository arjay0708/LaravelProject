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

class Customer extends Controller
{
    // ROUTES
        public function customerDashboard(){
            return view('customer/dashboard');
        }
        public function customerRoom(){
            return view('customer/room');
        }
        public function customerReservation(){
            return view('customer/reservation');
        }
        public function customerAcceptReservation(){
            return view('customer/acceptReservation');
        }
        public function customerDeclinedReservation(){
            return view('customer/declinedReservation');
        }
        public function customerCompleted(){
            return view('customer/complete');
        }
        public function customerAccount(){
            return view('customer/account');
        }
    // ROUTES

    // FUNCTION
        // SHOW ROOM FOR CUSTOMER
            public function getCustomerRoom(Request $request){
                $data = roomModel::where([['is_available', '!=', 0]])->orderBy('room_id')->get();
                if($data->isNotEmpty()){
                    foreach($data as $item){
                        echo"
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
                                                            Price Per Hour:<span class='fw-normal'> ₱$item->price_per_hour.00</span>
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
                }else{
                    echo "
                    <div class='row applicantNoSchedule' style='margin-top:18rem; color: #303030;'>
                        <div class='alert alert-light text-center fs-4' role='alert' style='color: #303030;'>
                            NO ROOM AVAILABLE
                        </div>
                    </div>
                    ";
                }
            }
        // SHOW ROOM FOR CUSTOMER

        // BOOK RESERVATION
            public function bookReservation(Request $request){
                $data = reservationModel::where([['user_id', '=', auth()->guard('userModel')->user()->user_id], ['room_id', '=', $request->roomId],
                ['start_dataTime', '=', $request->checkInDateTime],['end_dateTime', '=', $request->checkOutDateTime]])->get();
                if($data->isNotEmpty()){
                    return response()->json(6);
                    exit();
                }else{
                    date_default_timezone_set('Asia/Manila');
                    $currentDateTime = date('m-d-Y h:i A');
                    $checkInDateTime = date('m-d-Y h:i A',strtotime($request->checkInDateTime));
                    $checkOutDateTime = date('m-d-Y h:i A',strtotime($request->checkOutDateTime));
                    $data = userModel::where([['user_id', '=', auth()->guard('userModel')->user()->user_id]])->get();
                    foreach($data as $certainData){
                        if($certainData->lastname == "" && $certainData->firstname == ""){
                            echo 5;
                            exit();
                        }else{
                            if($currentDateTime > $checkInDateTime){
                                return response()->json(4);
                                exit();
                            }else if($checkInDateTime == $checkOutDateTime){
                                return response()->json(2);
                                exit();
                            }else if($checkOutDateTime < $checkInDateTime ){
                                return response()->json(3);
                                exit();
                            }else{
                                $bookRoom = reservationModel::create([
                                    'user_id' =>  auth()->guard('userModel')->user()->user_id,
                                    'room_id' => $request->roomId,
                                    'start_dataTime' => $request->checkInDateTime,
                                    'end_dateTime' => $request->checkOutDateTime,
                                    'status' => 'Pending',
                                    'is_archived' => 0
                                    ]);
                                    return response()->json($bookRoom ? 1 : 0);
                                exit();
                            }
                        }
                    }
                }
            }
        // BOOK RESERVATION

        // CANCEL RESERVATION
            public function cancelReservation(Request $request){
                $cancelReservation = reservationModel::where([['reservation_id', '=', $request->reservationID]])->delete();
                return response()->json($cancelReservation ? 1 : 0);
            }
        // CANCEL RESERVATION

        // RESERVATION RESERVATION PER USER
            public function getBookPerUser(Request $request){
                $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
                ->where([['reservationTable.status', '=', 'Pending'],['reservationTable.user_id', '=' ,auth()->guard('userModel')->user()->user_id]]
                )->orderBy('reservationTable.reservation_id', 'ASC')->get();
                if($data->isNotEmpty()){
                    foreach($data as $item){
                        // CALCULATE OF TOTAL HOURS
                            $checkInDateTime = date('F d, Y g:i A',strtotime($item->start_dataTime));
                            $checkOutDateTime = date('F d, Y g:i A',strtotime($item->end_dateTime));

                            $carbonStart = Carbon::parse($checkInDateTime);
                            $carbonEnd = Carbon::parse($checkOutDateTime);

                            $totalHours = $carbonStart->diffInHours($carbonEnd);
                        // CALCULATE OF TOTAL HOURS

                        // CALCULATE OF TOTAL PAYMENT
                            $totalPayment = $totalHours * $item->price_per_hour;
                        // CALCULATE OF TOTAL PAYMENT
                        echo"
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
                                                            Price Per Hour:<span class='fw-normal'> ₱$item->price_per_hour.00</span>
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
                                                            Check In: <span class='fw-normal'> $checkInDateTime</span><br>
                                                            Check Out:<span class='fw-normal'> $checkOutDateTime</span>
                                                        </div>
                                                        <div class='col-12 col-lg-5 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                            Total Hours: <span class='fw-normal'> $totalHours Hours</span><br>
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
                }else{
                    echo "
                    <div class='row applicantNoSchedule' style='margin-top:18rem; color: #303030;'>
                        <div class='alert alert-light text-center fs-4' role='alert' style='color: #303030;'>
                            NO RESERVATION FOUND
                        </div>
                    </div>
                    ";
                }
            }
        // RESERVATION RESERVATION PER USER

        // ACCEPT RESERVATION PER USER
            public function getAcceptBookPerUser(Request $request){
                $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
                ->where([['reservationTable.status', '=', 'Accept'],['reservationTable.user_id', '=' ,auth()->guard('userModel')->user()->user_id]]
                )->orderBy('reservationTable.reservation_id', 'ASC')->get();
                if($data->isNotEmpty()){
                    foreach($data as $item){
                        // CALCULATE OF TOTAL HOURS
                            $checkInDateTime = date('F d, Y g:i A',strtotime($item->start_dataTime));
                            $checkOutDateTime = date('F d, Y g:i A',strtotime($item->end_dateTime));

                            $carbonStart = Carbon::parse($checkInDateTime);
                            $carbonEnd = Carbon::parse($checkOutDateTime);

                            $totalHours = $carbonStart->diffInHours($carbonEnd);
                        // CALCULATE OF TOTAL HOURS

                        // CALCULATE OF TOTAL PAYMENT
                            $totalPayment = $totalHours * $item->price_per_hour;
                        // CALCULATE OF TOTAL PAYMENT
                        echo"
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
                                                            Price Per Hour:<span class='fw-normal'> ₱$item->price_per_hour.00</span>
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
                                                            Check In: <span class='fw-normal'> $checkInDateTime</span><br>
                                                            Check Out:<span class='fw-normal'> $checkOutDateTime</span>
                                                        </div>
                                                        <div class='col-12 col-lg-5 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                            Total Hours: <span class='fw-normal'> $totalHours Hours</span><br>
                                                            Total Payment:<span class='fw-normal'> ₱$totalPayment.00</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class='list-group-item text-center text-lg-end py-2'>
                                                    <button onclick='backOutReservation($item->reservation_id)' type='button' class='btn btn-sm btn-danger px-4 py-2 rounded-0'>CANCEL BOOK</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ";
                    }
                }else{
                    echo "
                    <div class='row applicantNoSchedule' style='margin-top:18rem; color: #303030;'>
                        <div class='alert alert-light text-center fs-4' role='alert' style='color: #303030;'>
                            NO RESERVATION FOUND
                        </div>
                    </div>
                    ";
                }
            }
        // ACCEPT RESERVATION PER USER

        // DECLINE RESERVATION PER USER
            public function getDeclineBookPerUser(Request $request){
                $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
                ->where([['reservationTable.status', '=', 'Decline'],['reservationTable.user_id', '=' ,auth()->guard('userModel')->user()->user_id]]
                )->orderBy('reservationTable.reservation_id', 'ASC')->get();
                if($data->isNotEmpty()){
                    foreach($data as $item){
                        // CALCULATE OF TOTAL HOURS
                            $checkInDateTime = date('F d, Y g:i A',strtotime($item->start_dataTime));
                            $checkOutDateTime = date('F d, Y g:i A',strtotime($item->end_dateTime));

                            $carbonStart = Carbon::parse($checkInDateTime);
                            $carbonEnd = Carbon::parse($checkOutDateTime);

                            $totalHours = $carbonStart->diffInHours($carbonEnd);
                        // CALCULATE OF TOTAL HOURS

                        // CALCULATE OF TOTAL PAYMENT
                            $totalPayment = $totalHours * $item->price_per_hour;
                        // CALCULATE OF TOTAL PAYMENT
                        echo"
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
                                                            Price Per Hour:<span class='fw-normal'> ₱$item->price_per_hour.00</span>
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
                                                            Check In: <span class='fw-normal'> $checkInDateTime</span><br>
                                                            Check Out:<span class='fw-normal'> $checkOutDateTime</span>
                                                        </div>
                                                        <div class='col-12 col-lg-5 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                            Total Hours: <span class='fw-normal'> $totalHours Hours</span><br>
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
                }else{
                    echo "
                    <div class='row applicantNoSchedule' style='margin-top:18rem; color: #303030;'>
                        <div class='alert alert-light text-center fs-4' role='alert' style='color: #303030;'>
                            NO RESERVATION FOUND
                        </div>
                    </div>
                    ";
                }
            }
        // DECLINE RESERVATION PER USER

        // COMPLETE RESERVATION PER USER
            public function getCompleteBookPerUser(Request $request){
                $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
                ->where([['reservationTable.status', '=', 'Complete'],['reservationTable.user_id', '=' ,auth()->guard('userModel')->user()->user_id]]
                )->orderBy('reservationTable.reservation_id', 'ASC')->get();
                if($data->isNotEmpty()){
                    foreach($data as $item){
                        // CALCULATE OF TOTAL HOURS
                            $checkInDateTime = date('F d, Y g:i A',strtotime($item->start_dataTime));
                            $checkOutDateTime = date('F d, Y g:i A',strtotime($item->end_dateTime));

                            $carbonStart = Carbon::parse($checkInDateTime);
                            $carbonEnd = Carbon::parse($checkOutDateTime);

                            $totalHours = $carbonStart->diffInHours($carbonEnd);
                        // CALCULATE OF TOTAL HOURS

                        // CALCULATE OF TOTAL PAYMENT
                            $totalPayment = $totalHours * $item->price_per_hour;
                        // CALCULATE OF TOTAL PAYMENT
                        echo"
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
                                                            Price Per Hour:<span class='fw-normal'> ₱$item->price_per_hour.00</span>
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
                                                            Check In: <span class='fw-normal'> $checkInDateTime</span><br>
                                                            Check Out:<span class='fw-normal'> $checkOutDateTime</span>
                                                        </div>
                                                        <div class='col-12 col-lg-5 pt-2 pt-lg-0 ps-0 ps-lg-4'>
                                                            Total Hours: <span class='fw-normal'> $totalHours Hours</span><br>
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
                }else{
                    echo "
                    <div class='row applicantNoSchedule' style='margin-top:21rem; color: #303030;'>
                        <div class='alert alert-light text-center fs-4' role='alert' style='color: #303030;'>
                            NO RESERVATION FOUND
                        </div>
                    </div>
                    ";
                }
            }
        // COMPLETE RESERVATION PER USER

        // COUNT ROOM AVAILABLE
            public function totalAvailableRoom(Request $request){
                $data = roomModel::where('is_available', '=', 1)->get();
                $countData = $data->count();
                return response()->json($countData != '' ? $countData : '0');
            }
        // COUNT ROOM AVAILABLE

        // TOTAL PENDING RESERVATION
            public function totalPendingReservation(Request $request){
                $data = reservationModel::where([['user_id', '=', auth()->guard('userModel')->user()->user_id],
                ['status', '=' ,'Pending']])->get();
                $countData = $data->count();
                return response()->json($countData != '' ? $countData : '0');
            }
        // TOTAL PENDING RESERVATION

        // TOTAL ACCEPT RESERVATION
            public function totalAcceptReservation(Request $request){
                $data = reservationModel::where([['user_id', '=', auth()->guard('userModel')->user()->user_id],
                ['status', '=' ,'Accept']])->get();
                $countData = $data->count();
                return response()->json($countData != '' ? $countData : '0');
            }
        // TOTAL ACCEPT RESERVATION

        // TOTAL DECLINE RESERVATION
            public function totalDeclineReservation(Request $request){
                $data = reservationModel::where([['user_id', '=', auth()->guard('userModel')->user()->user_id],
                ['status', '=' ,'Decline']])->get();
                $countData = $data->count();
                return response()->json($countData != '' ? $countData : '0');
            }
        // TOTAL DECLINE RESERVATION

        // TOTAL COMPLETE RESERVATION
            public function totalCompleteReservation(Request $request){
                $data = reservationModel::where([['user_id', '=', auth()->guard('userModel')->user()->user_id],
                ['status', '=' ,'Complete']])->get();
                $countData = $data->count();
                return response()->json($countData != '' ? $countData : '0');
            }
        // TOTAL COMPLETE RESERVATION

        // BACK OUT CONTENT
            public function getBackOutContent(Request $request){
                $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
                ->join('reasonBackOutTable', 'reservationTable.reservation_id', '=', 'reasonBackOutTable.reservation_id')
                ->where([['reservationTable.status', '=', 'BackOut'],['reservationTable.user_id', '=' ,auth()->guard('userModel')->user()->user_id]
                ,['reservationTable.is_archived', '=', 0],['reasonBackOutTable.set_by_admin', '=', 1]])
                ->select('roomTable.room_number','roomTable.floor','reasonBackOutTable.reservation_id','reservationTable.start_dataTime',
                'reservationTable.end_dateTime',
                'reasonBackOutTable.reason')->orderBy('reservationTable.start_dataTime' , 'ASC')->get();
                $customer = auth()->guard('userModel')->user()->firstname.' '.
                auth()->guard('userModel')->user()->lastname.' '.auth()->guard('userModel')->user()->extention;
                if($data->isNotEmpty()){
                    foreach($data as $item){
                        $newStartDate = date('F d, Y - h:i: A',strtotime($item->start_dataTime));
                        $newEndDate = date('F d, Y - h:i: A',strtotime($item->end_dateTime));
                        echo"
                            <div class='col-12'>
                                <div class='card mb-2 shadow'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>Dear $customer,</h5>
                                        <p class='card-text mb-3'>Your Reservation for <span class='fw-bold'>$item->floor Room Number $item->room_number From
                                        $newStartDate to $newEndDate </span> has been cancelled due to $item->reason. We apologize for the inconvenience i hope you understand.
                                        Please book another room, Thank You And God Bless.</p>
                                        <button onclick=noteBackOutContent('$item->reservation_id') class='btn btn-success btn-sm px-3 rounded-0'>Noted</button>
                                    </div>
                                </div>
                            </div>
                        ";
                    }
                }else{
                    echo "
                    <div class='row applicantNoSchedule' style='margin-top:1.5rem; color: #303030;'>
                        <div class='alert alert-light text-center' role='alert' style='color: #303030; font-size:18px; font-weight:bold'>
                            NO CANCELLED RESERVATION
                        </div>
                    </div>
                    ";
                }
            }
        // BACK OUT CONTENT

        // ARCHIVED CANCELLED RESERVATION
            public function archivedCancelledReservation(Request $request){
                $archive = reservationModel::where([['reservation_id', '=', $request->reservationId]])
                ->update(['is_archived' => 1]);
                return response()->json($archive ? 1 : 0);
            }
        // ARCHIVED CANCELLED RESERVATION

        // CANCEL THE ACCEPTED RESERVATION
            public function backOutReservation(Request $request){
                $backOutReservation = reservationModel::where([['reservation_id', '=', $request->reservationId]])
                ->update(['status' => 'BackOut']);
                if($backOutReservation){
                    $backOutReason = reasonBackOutModel::create([
                        'reservation_id' => $request->reservationId,
                        'user_id' => auth()->guard('userModel')->user()->user_id,
                        'reason' => $request->reason,
                        'set_by_admin' => 0,
                    ]);
                    return response()->json($backOutReason ? 1 : 0);
                }
            }
        // CANCEL THE ACCEPTED RESERVATION

        // FETCH ACCOUNT PER USER
            public function getUserInfo(Request $request){
                $data = userModel::where([['user_id', '=', auth()->guard('userModel')->user()->user_id]])->first();
                return response()->json($data);
            }
        // FETCH ACCOUNT PER USER

        // FETCH UPDATE ACCOUNT PER USER
        public function updateUserAccount(Request $request){
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
            $update->extention = $request->input('userExtention');
            $update->email = $request->input('userEmail');
            $update->phoneNumber = $request->input('userPhoneNumber');
            $update->birthday = $request->input('userBirthday');
            $update->age = $request->input('userAge');
            $update->save();

            return response()->json(1);
        }

        // FETCH UPDATE ACCOUNT PER USER
    // FUNCTION
}
