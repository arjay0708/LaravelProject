<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Models\userModel;
use Auth;    
use Session;
       
class Authentication extends Controller
{
    // ROUTING 
        public function login(){
            return view('login');
        }   

        public function registration(){
            return view('registration');
        }   
    // ROUTING 

    // FUNCTION FOR REGISTRATION 
        public function registrationFunction(Request $request){
            $existingEmail = userModel::select('email')->where('email','=',$request->email)->get();
            if($existingEmail->isNotEmpty()){
                return response()->json(2); // CHOOSE ANOTHER EMAIL
            }else{
                $registration = userModel::create([
                    'photos' => '/storage/userPhotos/defaultImage.png',
                    'email' => $request->email,
                    'password' => Hash::make($request->userRegisterPassword),
                    'is_active' => 1,
                    'is_admin' => 0,
                ]);
                return response()->json($registration ? 1 : 0);
            }
        }

    // FUNCTION FOR REGISTRATION

    // FUNCTION FOR LOGIN
        protected function userCredentials(Request $request){
            return [
                'email' => request()->{$this->userEmail()},
                'password' => request()->userLoginPassword,
            ];
        }

        protected function userEmail(){
            return 'userEmail';
        }  

        public function userLoginFunction(Request $request){
            if(auth()->guard('userModel')->attempt($this->userCredentials($request))){
                if(auth()->guard('userModel')->user()->is_active != 0 ){
                    if(auth()->guard('userModel')->user()->is_admin == 1){
                        $request->session()->regenerate();
                        return response()->json(1);
                    }else{
                        $request->session()->regenerate();
                        return response()->json(2);
                    }
                }else{
                    // INACTIVE ACCOUNT
                    return response()->json(3);
                }
            }else{
                // WRONG CREDENTIALS
                return response()->json(0);
            }
        }
    // FUNCTION FOR LOGIN

    // LOGOUT FUNCTION
        public function logoutFunction(){
            $updateUtilized = userModel::where('user_id', '=', auth()->guard('userModel')->user()->user_id)
            ->update(array('updated_at' => now()));
            if($updateUtilized){
                Session::flush();
                Auth::logout();
                return response()->json(1);
            }
        }
    // LOGOUT FUNCTION
}
