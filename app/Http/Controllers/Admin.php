<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\userModel;
use App\Models\roomModel;
use App\Models\reservationModel;
use App\Models\reasonDeclineModel;
use App\Models\reasonBackOutModel;

class Admin extends Controller
{
    // ROUTES
        public function adminDashboard(){
            return view('admin/dashboard');
        }   
        public function adminRoom(){
            return view('admin/room');
        }   
        public function adminNotAvailableRoom(){
            return view('admin/notAvailableRoom');
        }   
        public function addNewRoom(){
            return view('admin/addNewRoom');
        }   
        public function adminReservation(){
            return view('admin/reservation');
        }   
        public function adminAcceptReservation(){
            return view('admin/acceptReservation');
        }   
        public function adminOnGoingReservation(){
            return view('admin/ongoingReservation');
        }   
        public function adminDeclineReservation(){
            return view('admin/declineReservation');
        }  
        public function adminBackOutReservation(){
            return view('admin/backOutReservation');
        }  
        public function adminCompleted(){
            return view('admin/completed');
        }   
        public function adminCustomer(){
            return view('admin/customer');
        }   
        public function adminInActiveCustomer(){
            return view('admin/inactiveCustomer');
        }   
        public function adminAccount(){
            return view('admin/account');
        }   
    // ROUTES

    // FUNCTION
            // ACTIVE USER DATA FOR TABLE
                   public function getActiveCustomer(Request $request){  
                    $data = userModel::where([['is_active', '=', 1],['is_admin', '=', 0],['lastname', '!=', 'NULL']])->select(
                        'user_id','lastname','firstname','middlename','extention','email','phoneNumber'
                    )->get();
                    return response()->json($data);
                }  
            // ACTIVE USER DATA FOR TABLE

            // ACTIVE USER DATA FOR TABLE
                public function getInActiveCustomer(Request $request){  
                    $data = userModel::where([['is_active', '=', 0],['is_admin', '=', 0],['lastname', '!=', 'NULL']])->select(
                        'user_id','lastname','firstname','middlename','extention','email','phoneNumber'
                    )->get();
                    return response()->json($data);
                }  
            // ACTIVE USER DATA FOR TABLE

            // PERSONAL INFORMATION FOR ACCOUNT
                public function getUserInfo(Request $request){  
                    $data = userModel::where([['user_id', '=', auth()->guard('userModel')->user()->user_id]])->first();
                    return response()->json($data);
                }  
            // PERSONAL INFORMATION FOR ACCOUNT

            // AVAILABLE ROOM FOR TABLE
                public function getAvailableRoom(Request $request){  
                    $data = roomModel::where([['is_available', '=', 1]])->select(
                        'room_id','room_number','floor','type_of_room','price_per_hour'
                    )->get();
                    return response()->json($data);
                } 
            // AVAILABLE ROOM FOR TABLE

            // NOT AVAILABLE ROOM FOR TABLE
                public function getNotAvailableRoom(Request $request){  
                    $data = roomModel::where([['is_available', '=', 0]])->select(
                        'room_id','room_number','floor','type_of_room','price_per_hour'
                    )->get();
                    return response()->json($data);
                } 
            // NOT AVAILABLE ROOM FOR TABLE

            // ADD ROOM FUNCTION
                public function addRoom(Request $request){ 
                    $filename = $request->file('roomPhoto');
                    $imageName =   time().rand() . '.' .  $filename->getClientOriginalExtension();
                    $path = $request->file('roomPhoto')->storeAs('roomPhotos', $imageName, 'public');
                    $imageData['roomPhoto'] = '/storage/'.$path;
                    $addRoom = roomModel::create([
                    'photos' => $imageData['roomPhoto'],
                    'room_number' => $request->roomNumber,
                    'floor' => $request->roomFloor,
                    'type_of_room' => $request->roomType,
                    'number_of_bed' => $request->bedNumber,
                    'details' => $request->detailsOfRoom,
                    'max_person' => $request->maxPerson,
                    'price_per_hour' => $request->pricePerHour,
                    'is_available' => 1
                    ]);
                    return response()->json($addRoom ? 1 : 0);
                    exit();
                }
            // ADD ROOM FUNCTION

            // ALL PENDING RESERVATION
                public function getAllPendingReservation(Request $request){ 
                    $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
                    ->join('userTable', 'reservationTable.user_id', '=', 'userTable.user_id')
                    ->where([['reservationTable.status', '=', 'Pending']])->orderBy('reservationTable.reservation_id', 'ASC')
                    ->select(
                        'reservationTable.reservation_id', 'userTable.user_id','userTable.lastname','userTable.firstname','userTable.middlename',
                        'userTable.extention','roomTable.room_number','roomTable.floor','reservationTable.start_dataTime','reservationTable.end_dateTime',
                    )->orderBy('reservationTable.start_dataTime' , 'ASC')->get();
                    return response()->json($data);
                }
            // ALL PENDING RESERVATION

            // ALL ACCEPT RESERVATION
                public function getAllAcceptReservation(Request $request){ 
                    $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
                    ->join('userTable', 'reservationTable.user_id', '=', 'userTable.user_id')
                    ->where([['reservationTable.status', '=', 'Accept']])->orderBy('reservationTable.reservation_id', 'ASC')
                    ->select(
                        'reservationTable.reservation_id','userTable.user_id','userTable.lastname','userTable.firstname','userTable.middlename','userTable.extention',
                        'roomTable.room_id', 'roomTable.room_number','roomTable.floor','reservationTable.start_dataTime','reservationTable.end_dateTime',
                    )->orderBy('reservationTable.start_dataTime' , 'ASC')->get();
                    return response()->json($data);
                }
            // ALL ACCEPT RESERVATION
            
            // ALL DECLINE RESERVATION
                public function getAllDeclineReservation(Request $request){ 
                    $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
                    ->join('userTable', 'reservationTable.user_id', '=', 'userTable.user_id')
                    ->where([['reservationTable.status', '=', 'Decline']])->orderBy('reservationTable.reservation_id', 'ASC')
                    ->select(
                        'reservationTable.reservation_id','userTable.user_id','userTable.lastname','userTable.firstname','userTable.middlename','userTable.extention',
                        'roomTable.room_number','roomTable.floor','reservationTable.start_dataTime','reservationTable.end_dateTime'
                    )->orderBy('reservationTable.start_dataTime' , 'ASC')->get();
                    return response()->json($data);
                }
            // ALL DECLINE RESERVATION

            // ALL ON GOING RESERVATION
                public function getAllOnGoingReservation(Request $request){ 
                    $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
                    ->join('userTable', 'reservationTable.user_id', '=', 'userTable.user_id')
                    ->where([['reservationTable.status', '=', 'OnGoing']])->orderBy('reservationTable.reservation_id', 'ASC')
                    ->select(
                        'reservationTable.reservation_id','userTable.user_id','userTable.lastname','userTable.firstname','userTable.middlename','userTable.extention',
                        'roomTable.room_id', 'roomTable.room_number','roomTable.floor','reservationTable.start_dataTime','reservationTable.end_dateTime',
                    )->orderBy('reservationTable.start_dataTime' , 'ASC')->get();
                    return response()->json($data);
                }  
            // ALL ON GOING RESERVATION

            // ALL COMPLETED RESERVATION
                public function getAllCompletedReservation(Request $request){ 
                    $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
                    ->join('userTable', 'reservationTable.user_id', '=', 'userTable.user_id')
                    ->where([['reservationTable.status', '=', 'Complete']])->orderBy('reservationTable.reservation_id', 'ASC')
                    ->select(
                        'reservationTable.reservation_id','userTable.user_id','userTable.lastname','userTable.firstname','userTable.middlename','userTable.extention',
                        'roomTable.room_id', 'roomTable.room_number','roomTable.floor','reservationTable.start_dataTime','reservationTable.end_dateTime',
                    )->orderBy('reservationTable.end_dateTime' , 'ASC')->get();
                    return response()->json($data);
                }
            // ALL COMPLETED RESERVATION

            // ALL BACK OUT RESERVATION
                public function getAllBackOutReservation(Request $request){ 
                    $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
                    ->join('userTable', 'reservationTable.user_id', '=', 'userTable.user_id')
                    ->join('reasonBackOutTable', 'reservationTable.reservation_id', '=', 'reasonBackOutTable.reservation_id')
                    ->where([['reservationTable.status', '=', 'BackOut'], ['reasonBackOutTable.set_by_admin', '=', 0]
                    ])->orderBy('reservationTable.reservation_id', 'ASC')
                    ->select(
                        'reservationTable.reservation_id','userTable.user_id','userTable.lastname','userTable.firstname','userTable.middlename','userTable.extention',
                        'roomTable.room_number','roomTable.floor','reservationTable.start_dataTime','reservationTable.end_dateTime'
                    )->orderBy('reservationTable.start_dataTime' , 'ASC')->get();
                    return response()->json($data);
                }
            // ALL BACK OUT RESERVATION

            // ACCEPT RESERVATION
                public function acceptReservation(Request $request){ 
                    $acceptReservation = reservationModel::where([['reservation_id', '=', $request->reservationId]])
                    ->update(['status' => 'Accept']);
                    return response()->json(1);
                }
            // ACCEPT RESERVATION

            // ON-GOING RESERVATION
                public function ongoingReservation(Request $request){ 
                    date_default_timezone_set('Asia/Manila');
                    $currentDate = date('m-d-Y h:i A', strtotime(now()));
                    $data = reservationModel::where([['reservation_id', '=', $request->reservationId]])->first();
                    $checkInDateTime = date('m-d-Y h:i A',strtotime($data->start_dataTime));
                    $checkOutDateTime = date('m-d-Y h:i A',strtotime($data->end_dateTime));
                    if($currentDate >= $checkInDateTime && $currentDate <= $checkOutDateTime){
                        $ongoingReservation = reservationModel::where([['reservation_id', '=', $request->reservationId]])->update(['status' => 'OnGoing']);
                        if($ongoingReservation){
                            $notAvailableRoom = roomModel::where([['room_id', '=', $request->roomId]])
                            ->update(['is_available' => 0]);
                            return response()->json($notAvailableRoom ? 1 : 0);
                        }
                    }else{
                        return response()->json(2);
                    }
                }
            // ON-GOING RESERVATION

            // DECLINE RESERVATION
                public function declineReservation(Request $request){ 
                    $declineReservation = reservationModel::where([['reservation_id', '=', $request->reservationId]])
                    ->update(['status' => 'Decline']);
                    if($declineReservation){
                        $declineReason = reasonDeclineModel::create([
                            'reservation_id' => $request->reservationId,
                            'user_id' => $request->userId,
                            'reason' => $request->reason,
                        ]);
                        return response()->json($declineReason ? 1 : 0);
                    }
                }
            // DECLINE RESERVATION

            // COMPLETE RESERVATION
                public function completeReservation(Request $request){ 
                    date_default_timezone_set('Asia/Manila');
                    $currentDate = date('m-d-Y h:i A', strtotime("+1 hours", strtotime(now())));
                    $data = reservationModel::where([['reservation_id', '=', $request->reservationId]])->first();
                    $checkOutDateTime = date('m-d-Y h:i A',strtotime($data->end_dateTime));
                    if($currentDate > $checkOutDateTime){
                        $completeReservation = reservationModel::where([['reservation_id', '=', $request->reservationId]])->update(['status' => 'Complete']);
                        $availableRoom = roomModel::where([['room_id', '=', $request->roomId]])->update(['is_available' => 1]);
                        return response()->json($completeReservation ? 1 : 0);
                    }else{
                        return response()->json(2);
                    }
                }
            // COMPLETE RESERVATION

            // BACK OUT RESERVATION
                public function backOutReservation(Request $request){ 
                    $backOutReservation = reservationModel::where([['reservation_id', '=', $request->reservationId]])
                    ->update(['status' => 'BackOut']);
                    if($backOutReservation){
                        $backOutReason = reasonBackOutModel::create([
                            'reservation_id' => $request->reservationId,
                            'user_id' => $request->userId,
                            'reason' => $request->reason,
                            'set_by_admin' => 1,
                        ]);
                        return response()->json($backOutReason ? 1 : 0);
                    }
                }
            // BACK OUT RESERVATION

            // TOTAL PENDING RESERVATION
                public function totalPendingReservation(Request $request){
                    $data = reservationModel::where([['status', '=' ,'Pending']])->get();
                    $countData = $data->count();
                    return response()->json($countData != '' ? $countData : '0');
                }
            // TOTAL PENDING RESERVATION

            // TOTAL ON-GOING RESERVATION
                public function totalOnGoingReservation(Request $request){
                    $data = reservationModel::where([['status', '=' ,'OnGoing']])->get();
                    $countData = $data->count();
                    return response()->json($countData != '' ? $countData : '0');
                }
            // TOTAL ON-GOING RESERVATION

            // TOTAL COMPLETED RESERVATION
                public function totalCompletedReservation(Request $request){
                    $data = reservationModel::where([['status', '=' ,'Completed']])->get();
                    $countData = $data->count();
                    return response()->json($countData != '' ? $countData : '0');
                }
            // TOTAL COMPLETED RESERVATION

            // TOTAL CUSTOMER
                public function totalCustomer(Request $request){
                    $data = userModel::where([['is_active', '=' ,1], ['is_admin', '=' ,0]])->get();
                    $countData = $data->count();
                    return response()->json($countData != '' ? $countData : '0');
                }
            // TOTAL CUSTOMER
    // FUNCTION
}
