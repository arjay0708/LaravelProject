<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\userModel;
use App\Models\roomModel;
use App\Models\reservationModel;

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
                                                ";
                                                $checkStatus = reservationModel::where([['user_id', '=',  auth()->guard('userModel')->user()->user_id],
                                                ['room_id' ,'=', $item->room_id],['status' ,'=', 'Pending']])->get();
                                                if(!$checkStatus->isEmpty()){
                                                    echo"
                                                        <button onclick='cancelReservation($item->room_id)' type='button' class='btn btn-sm btn-danger px-4 py-2 rounded-0'>CANCEL BOOK</button>
                                                    ";
                                                }else{
                                                    echo"
                                                        <button onclick='bookReservation($item->room_id)' type='button' class='btn btn-sm btn-dark px-4 py-2 rounded-0'>BOOK NOW</button>
                                                    ";
                                                }
                                                echo"
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
                    <div class='row applicantNoSched' style='margin-top:15rem; color: #303030;'>
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
        // BOOK RESERVATION

        // CANCEL RESERVATION
            public function cancelReservation(Request $request){
                $cancelReservation = reservationModel::where([['user_id', '=', auth()->guard('userModel')->user()->user_id],
                ['room_id', '=', $request->reservationID]])->delete();
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
                                                ";
                                                $checkStatus = reservationModel::where([['user_id', '=',  auth()->guard('userModel')->user()->user_id],
                                                ['room_id' ,'=', $item->room_id],['status' ,'=', 'Pending']])->get();
                                                if(!$checkStatus->isEmpty()){
                                                    echo"
                                                        <button onclick='cancelReservation($item->room_id)' type='button' class='btn btn-sm btn-danger px-4 py-2 rounded-0'>CANCEL BOOK</button>
                                                    ";
                                                }else{
                                                    echo"
                                                        <button onclick='bookReservation($item->room_id)' type='button' class='btn btn-sm btn-dark px-4 py-2 rounded-0'>BOOK NOW</button>
                                                    ";
                                                }
                                                echo"
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
                    <div class='row applicantNoSched' style='margin-top:15rem; color: #303030;'>
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
                                                ";
                                                $checkStatus = reservationModel::where([['user_id', '=',  auth()->guard('userModel')->user()->user_id],
                                                ['room_id' ,'=', $item->room_id],['status' ,'=', 'Pending']])->get();
                                                if(!$checkStatus->isEmpty()){
                                                    echo"
                                                        <button onclick='cancelReservation($item->room_id)' type='button' class='btn btn-sm btn-danger px-4 py-2 rounded-0'>CANCEL BOOK</button>
                                                    ";
                                                }else{
                                                    echo"
                                                        <button onclick='bookReservation($item->room_id)' type='button' class='btn btn-sm btn-dark px-4 py-2 rounded-0'>BOOK NOW</button>
                                                    ";
                                                }
                                                echo"
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
                    <div class='row applicantNoSched' style='margin-top:15rem; color: #303030;'>
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
                                                ";
                                                $checkStatus = reservationModel::where([['user_id', '=',  auth()->guard('userModel')->user()->user_id],
                                                ['room_id' ,'=', $item->room_id],['status' ,'=', 'Pending']])->get();
                                                if(!$checkStatus->isEmpty()){
                                                    echo"
                                                        <button onclick='cancelReservation($item->room_id)' type='button' class='btn btn-sm btn-danger px-4 py-2 rounded-0'>CANCEL BOOK</button>
                                                    ";
                                                }else{
                                                    echo"
                                                        <button onclick='bookReservation($item->room_id)' type='button' class='btn btn-sm btn-dark px-4 py-2 rounded-0'>BOOK NOW</button>
                                                    ";
                                                }
                                                echo"
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
                    <div class='row applicantNoSched' style='margin-top:15rem; color: #303030;'>
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
                    <div class='row applicantNoSched' style='margin-top:15rem; color: #303030;'>
                        <div class='alert alert-light text-center fs-4' role='alert' style='color: #303030;'>
                            NO RESERVATION FOUND
                        </div>
                    </div>
                    ";
                }
            }
        // COMPLETE RESERVATION PER USER
    // FUNCTION
}
