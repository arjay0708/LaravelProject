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
        public function adminOnGoingReservation(){
            return view('admin/ongoingReservation');
        }
        public function adminCancelledReservation(){
            return view('admin/cancelledReservation');
        }
        public function adminUnpaidReservation(){
            return view('admin/unpaidReservation');
        }
        public function adminCompletedReservation(){
            return view('admin/completedReservation');
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

            // IN-ACTIVE USER DATA FOR TABLE
            public function getInActiveCustomer(Request $request){
                $data = userModel::where([['is_active', '=', 0],['is_admin', '=', 0],['lastname', '!=', 'NULL']])->select(
                    'user_id','lastname','firstname','middlename','extention','email','phoneNumber'
                )->get();
                return response()->json($data);
            }

            // PERSONAL INFORMATION FOR ACCOUNT
            public function getUserInfo(Request $request){
                $data = userModel::where([['user_id', '=', auth()->guard('userModel')->user()->user_id]])->first();
                return response()->json($data);
            }

            // AVAILABLE ROOM FOR TABLE
            public function getAvailableRoom(Request $request){
                $data = roomModel::where([['is_available', '=', 1]])->select(
                    'room_id','room_number','floor','type_of_room','price_per_hour'
                )->get();
                return response()->json($data);
            }

            // NOT AVAILABLE ROOM FOR TABLE
            public function getNotAvailableRoom(Request $request){
                $data = roomModel::where([['is_available', '=', 0]])->select(
                    'room_id','room_number','floor','type_of_room','price_per_hour'
                )->get();
                return response()->json($data);
            }

            // ADD ROOM FUNCTION
            public function addRoom(Request $request){
                $data = roomModel::where([['room_number', '=', $request->roomNumber]])->get();
                if(!$data->isNotEmpty()){
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
                }else{
                    return response()->json(2);
                }
            }

            // ALL PENDING RESERVATION
            public function getAllPendingReservation(Request $request) {
                $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
                    ->join('userTable', 'reservationTable.user_id', '=', 'userTable.user_id')
                    ->where([['reservationTable.status', '=', 'Pending']])
                    ->orderBy('reservationTable.reservation_id', 'ASC')
                    ->select(
                        'reservationTable.reservation_id', 'userTable.user_id', 'userTable.lastname', 'userTable.firstname', 'userTable.middlename',
                        'userTable.extention', 'roomTable.room_number', 'roomTable.floor', 'roomTable.price_per_hour', 'reservationTable.start_dataTime', 'reservationTable.end_dateTime',
                    )->orderBy('reservationTable.start_dataTime', 'ASC')->get();

                foreach ($data as $reservation) {
                    $startDateTime = Carbon::parse($reservation->start_dataTime);
                    $endDateTime = Carbon::parse($reservation->end_dateTime);

                    $totalNights = ceil($startDateTime->diffInHours($endDateTime) / 24);
                    $totalPayment = $totalNights * $reservation->price_per_hour;
                    $halfTotalPayment = $totalPayment / 2;

                    $reservation->totalNights = $totalNights;
                    $reservation->totalPayment = $totalPayment;
                    $reservation->balance = $halfTotalPayment;
                }

                return response()->json($data);
            }

            // ALL ON GOING RESERVATION
            public function getAllOnGoingReservation(Request $request){
                $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
                ->join('userTable', 'reservationTable.user_id', '=', 'userTable.user_id')
                ->where([['reservationTable.status', '=', 'OnGoing']])
                ->orderBy('reservationTable.reservation_id', 'ASC')
                ->select(
                    'reservationTable.reservation_id', 'userTable.user_id', 'userTable.lastname', 'userTable.firstname', 'userTable.middlename',
                    'userTable.extention', 'roomTable.room_number', 'roomTable.floor', 'roomTable.price_per_hour', 'reservationTable.start_dataTime', 'reservationTable.end_dateTime',
                )->orderBy('reservationTable.start_dataTime', 'ASC')->get();

                foreach ($data as $reservation) {
                    $startDateTime = Carbon::parse($reservation->start_dataTime);
                    $endDateTime = Carbon::parse($reservation->end_dateTime);

                    $totalNights = ceil($startDateTime->diffInHours($endDateTime) / 24);
                    $totalPayment = $totalNights * $reservation->price_per_hour;
                    $halfTotalPayment = $totalPayment / 2;

                    $reservation->totalNights = $totalNights;
                    $reservation->totalPayment = $totalPayment;
                    $reservation->balance = $halfTotalPayment;
                }

                return response()->json($data);
            }

            // ALL CANCELLED RESERVATION
            public function getAllCancelledReservation(Request $request){
                $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
                ->join('userTable', 'reservationTable.user_id', '=', 'userTable.user_id')
                ->where([['reservationTable.status', '=', 'Cancel']])
                ->orderBy('reservationTable.reservation_id', 'ASC')
                ->select(
                    'reservationTable.reservation_id', 'userTable.user_id', 'userTable.lastname', 'userTable.firstname', 'userTable.middlename',
                    'userTable.extention', 'roomTable.room_number', 'roomTable.floor', 'roomTable.price_per_hour', 'reservationTable.start_dataTime', 'reservationTable.end_dateTime',
                )->orderBy('reservationTable.start_dataTime', 'ASC')->get();

                foreach ($data as $reservation) {
                    $startDateTime = Carbon::parse($reservation->start_dataTime);
                    $endDateTime = Carbon::parse($reservation->end_dateTime);

                    $totalNights = ceil($startDateTime->diffInHours($endDateTime) / 24);
                    $totalPayment = $totalNights * $reservation->price_per_hour;
                    $halfTotalPayment = $totalPayment / 2;

                    $reservation->totalNights = $totalNights;
                    $reservation->totalPayment = $totalPayment;
                    $reservation->balance = $halfTotalPayment;
                }

                return response()->json($data);
            }

            // ALL UNPAID RESERVATION
            public function getAllUnpaidReservation(Request $request){
                $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
                ->join('userTable', 'reservationTable.user_id', '=', 'userTable.user_id')
                ->where([['reservationTable.status', '=', 'Unpaid']])
                ->orderBy('reservationTable.reservation_id', 'ASC')
                ->select(
                    'reservationTable.reservation_id', 'userTable.user_id', 'userTable.lastname', 'userTable.firstname', 'userTable.middlename',
                    'userTable.extention', 'roomTable.room_number', 'roomTable.floor', 'roomTable.price_per_hour', 'reservationTable.start_dataTime', 'reservationTable.end_dateTime',
                )->orderBy('reservationTable.start_dataTime', 'ASC')->get();

                foreach ($data as $reservation) {
                    $startDateTime = Carbon::parse($reservation->start_dataTime);
                    $endDateTime = Carbon::parse($reservation->end_dateTime);

                    $totalNights = ceil($startDateTime->diffInHours($endDateTime) / 24);
                    $totalPayment = $totalNights * $reservation->price_per_hour;
                    $halfTotalPayment = $totalPayment / 2;

                    $reservation->totalNights = $totalNights;
                    $reservation->totalPayment = $totalPayment;
                    $reservation->balance = $halfTotalPayment;
                }

                return response()->json($data);
            }

            // ALL COMPLETED RESERVATION
            public function getAllCompletedReservation(Request $request){
                $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
                ->join('userTable', 'reservationTable.user_id', '=', 'userTable.user_id')
                ->where([['reservationTable.status', '=', 'Complete']])->orderBy('reservationTable.reservation_id', 'ASC')
                ->select(
                    'reservationTable.reservation_id','userTable.user_id','userTable.lastname','userTable.firstname','userTable.middlename','userTable.extention',
                    'roomTable.room_id', 'roomTable.room_number','roomTable.floor','roomTable.price_per_hour','reservationTable.start_dataTime','reservationTable.end_dateTime',
                )->orderBy('reservationTable.end_dateTime' , 'ASC')->get();
                foreach ($data as $reservation) {
                    $startDateTime = Carbon::parse($reservation->start_dataTime);
                    $endDateTime = Carbon::parse($reservation->end_dateTime);

                    $totalNights = ceil($startDateTime->diffInHours($endDateTime) / 24);
                    $totalPayment = $totalNights * $reservation->price_per_hour;

                    $reservation->totalPayment = $totalPayment;
                }
                return response()->json($data);
            }

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

            // ACCEPT RESERVATION
            public function acceptReservation(Request $request){
                reservationModel::where([['reservation_id', '=', $request->reservationId]])->update(['status' => 'Accept']);
                return response()->json(1);
            }

            // ON-GOING RESERVATION
            public function ongoingReservation(Request $request){
                date_default_timezone_set('Asia/Manila');
                $currentDate = date('m-d-Y h:i A', strtotime(now()));
                $data = reservationModel::where([['reservation_id', '=', $request->reservationId]])->first();
                $checkInDateTime = date('m-d-Y h:i A',strtotime($data->start_dataTime));
                $checkOutDateTime = date('m-d-Y h:i A',strtotime($data->end_dateTime));
                if($currentDate >= $checkInDateTime && $currentDate <= $checkOutDateTime){
                    $ongoingReservation = reservationModel::where([['reservation_id', '=', $request->reservationId]])->update(['status' => 'OnGoing']);
                    return response()->json($ongoingReservation ? 1 : 0);
                }else{
                    return response()->json(2);
                }
            }

            // COMPLETE RESERVATION
            public function completeReservation(Request $request){
                date_default_timezone_set('Asia/Manila');
                $currentDate = date('m-d-Y h:i A', strtotime("+1 hours", strtotime(now())));
                $data = reservationModel::where([['reservation_id', '=', $request->reservationId]])->first();
                $checkOutDateTime = date('m-d-Y h:i A',strtotime($data->end_dateTime));
                if($currentDate > $checkOutDateTime){
                    $completeReservation = reservationModel::where([['reservation_id', '=', $request->reservationId]])->update(['status' => 'Complete']);
                    return response()->json($completeReservation ? 1 : 0);
                }else{
                    return response()->json(2);
                }
            }

            // BACK OUT RESERVATION
            public function adminBackOutReservationFunction(Request $request){
                $backOutReservation = reservationModel::where([['reservation_id', '=', $request->reservationId]])
                ->update(['status' => 'BackOut']);
                if($backOutReservation){
                    $backOutReason = reasonBackOutModel::create([
                        'reservation_id' => $request->reservationId,
                        'user_id' => $request->userId,
                        'reason' => $request->reason,
                        'set_by_admin' => 1
                    ]);
                    return response()->json($backOutReason ? 1 : 0);
                }
            }

            // TOTAL PENDING RESERVATION
            public function totalPendingReservationForAdmin(Request $request){
                $data = reservationModel::where([['status', '=' ,'Pending']])->get();
                $countData = $data->count();
                return response()->json($countData != '' ? $countData : '0');
            }

            // TOTAL ON-GOING RESERVATION
            public function totalOnGoingReservationForAdmin(Request $request){
                $data = reservationModel::where([['status', '=' ,'OnGoing']])->get();
                $countData = $data->count();
                return response()->json($countData != '' ? $countData : '0');
            }

            // TOTAL COMPLETED RESERVATION
            public function totalCompletedReservationForAdmin(Request $request){
                $data = reservationModel::where([['status', '=' ,'Completed']])->get();
                $countData = $data->count();
                return response()->json($countData != '' ? $countData : '0');
            }

            // TOTAL CUSTOMER
            public function totalCustomerForAdmin(Request $request){
                $data = userModel::where([['is_active', '=' ,1], ['is_admin', '=' ,0]])->get();
                $countData = $data->count();
                return response()->json($countData != '' ? $countData : '0');
            }

            // VIEW DETAILS OF ROOM
            public function viewRoomDetails(Request $request){
                $data = roomModel::where([['room_id', '=' ,$request->roomId]])->first();
                return response()->json($data);
            }

            // UPDATE ROOM
            public function updateRoom(Request $request) {
                $update = roomModel::find($request->room_id);

                if ($request->hasFile('roomPhoto')) {
                    $filename = $request->file('roomPhoto');
                    $imageName = time() . rand() . '.' . $filename->getClientOriginalExtension();
                    $path = $request->file('roomPhoto')->storeAs('roomPhotos', $imageName, 'public');
                    $update->photos = '/storage/' . $path;
                }

                $update->room_number = $request->input('roomNumber');
                $update->floor = $request->input('roomFloor');
                $update->type_of_room = $request->input('roomType');
                $update->number_of_bed = $request->input('roomBedNumber');
                $update->details = $request->input('detailsOfRoom');
                $update->max_person = $request->input('roomMaxPerson');
                $update->price_per_hour = $request->input('roomPricePerHour');
                $update->save();

                return response()->json(['success' => true]);
            }

            // DEACTIVATE ROOM
            public function deactivateRoom(Request $request){
                roomModel::where([['room_id', '=', $request->roomId]])->update(['is_available' => 0]);
                return response()->json(1);
            }

            // ACTIVATE ROOM
            public function activateRoom(Request $request){
                roomModel::where([['room_id', '=', $request->roomId]])->update(['is_available' => 1]);
                return response()->json(1);
            }

            // GET ALL BACK OUT RESERVATION
            public function getBackOutContentForAdmin(Request $request){
                $data = reservationModel::join('roomTable', 'reservationTable.room_id', '=', 'roomTable.room_id')
                ->join('reasonBackOutTable', 'reservationTable.reservation_id', '=', 'reasonBackOutTable.reservation_id')
                ->join('userTable', 'reasonBackOutTable.user_id', '=', 'userTable.user_id')
                ->where([['reservationTable.status', '=', 'BackOut'],['reservationTable.is_archived', '=', 0],
                ['reasonBackOutTable.set_by_admin', '=', 0]])
                ->select('roomTable.room_number','roomTable.floor','reasonBackOutTable.reservation_id','reservationTable.start_dataTime',
                'reservationTable.end_dateTime', 'userTable.lastname',  'userTable.firstname', 'userTable.extention',
                'reasonBackOutTable.reason')->orderBy('reservationTable.start_dataTime' , 'ASC')->get();
                if($data->isNotEmpty()){
                    foreach($data as $item){
                        $newStartDate = date('F d, Y - h:i: A',strtotime($item->start_dataTime));
                        $newEndDate = date('F d, Y - h:i: A',strtotime($item->end_dateTime));
                        $customer = $item->firstname.''.$item->lastname.''.$item->extention;
                        echo"
                            <div class='col-12'>
                                <div class='card mb-2 shadow'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>Dear Admin,</h5>
                                        <p class='card-text mb-3'><span class='fw-bold'> Mr/Ms. $customer </span> has been cancelled the reservation for <span class='fw-bold'>$item->floor Room Number $item->room_number From
                                        $newStartDate to $newEndDate </span> due to $item->reason.</p>
                                        <button onclick=noteBackOutContent('$item->reservation_id') class='btn btn-success btn-sm px-3 rounded-0'>Noted</button>
                                    </div>
                                </div>
                            </div>
                        ";
                    }
                }else{
                    echo "
                    <div class='row' style='margin-top:1.5rem; color: #303030;'>
                        <div class='alert alert-light text-center' role='alert' style='color: #303030; font-size:18px; font-weight:bold'>
                            NO CANCELLED RESERVATION
                        </div>
                    </div>
                    ";
                }
            }

            // FETCH THE CANCELLED REASON
            public function viewReasonCancelled(Request $request){
                $data = reasonBackOutModel::where([['reservation_id', '=', $request->reservationId]])->select('reason','created_at')->first();
                $formattedCreatedAt = Carbon::parse($data->created_at)->format('Y-m-d ');
                $data->created_at = $formattedCreatedAt;
                return response()->json($data);
            }

            // DEACTIVATE CUSTOMER
            public function deactivateCustomer(Request $request){
                userModel::where([['user_id', '=', $request->customerId]])->update(['is_active' => 0]);
                return response()->json(1);
            }

            // ACTIVATE CUSTOMER
            public function activateCustomer(Request $request){
                userModel::where([['user_id', '=', $request->customerId]])->update(['is_active' => 1]);
                return response()->json(1);
            }

            // VIEW CUSTOMER
            public function viewCustomer(Request $request){
                $viewCustomer = userModel::where([['user_id', '=', $request->customerId]])->first();
                return response()->json($viewCustomer);
            }

            // AUTOMATIC DELETE UNPAID RESERVATION
            public function deleteUnpaidReservation(Request $request) {
                $currentDateTime = Carbon::now()->toDateTimeString();
                ReservationModel::where([['status', '=', 'Unpaid'],['end_dateTime', '<', $currentDateTime],])->delete();
                return response()->json(1);
            }


    // FUNCTION
}
