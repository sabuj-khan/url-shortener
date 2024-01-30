<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    function userLoginPage(){
        return view('pages.auth.login-page');
    }

    function userRegisterPage(){
        return view('pages.auth.register-page');
    }

    function sendOTPPage(){
        return view('pages.auth.send-oto-page');
    }

    function verifyOTPPage(){
        return view('pages.auth.verify-otp-page');
    }


    function passwordResetPage(){
        return view('pages.auth.password-reset-page');
    }

    function dashboardPage(){
        return view('pages.dashboard.dashboard-page');
    }

    function profilePage(){
        return view('pages.dashboard.profile-page');
    }






    function userRegistration(Request $request){
        try{
            User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'password' => $request->input('password')
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'You have been registered successfully'
            ], 201);

        }
        catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => 'Request fail to register'
            ]);
        }
    }


    function userLoginAction(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');

        $count = User::where('email', '=', $email)->where('password', '=', $password)->first();

        if($count !== null){
            $token = JWTToken::createToken($email, $count->id);

            return response()->json([
                'status' => 'success',
                'message' => 'You are loggedin now',
                'token' => $token
            ])->cookie('token', $token, 60*60*24);
        }else{
            return response()->json([
                'status'=>'fail',
                'message'=>'Unauthorized'
            ]);
        }


    }


    function sendOTPCodeToEmail(Request $request){
        $email = $request->input('email');
        $otp = rand(100000, 999999);
        
        $count = User::where('email', '=', $email)->count();

        if($count == 1){
            // Send OTP code to user Email
            //Mail::to($email)->send(new OTPMail($otp));

            // update otp code to database
            User::where('email', '=', $email)->update(['otp' => $otp]);

            return response()->json([
                'status'=>'success',
                'message'=>'6 Digit OTP code has been sent to your email'
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'message'=>'unauthorized from user Controller'
            ]);
        }
    
    }

    function OTPVerification(Request $request){
        $email = $request->input('email');
        $otp = $request->input('otp');

        $count = User::where('email', '=', $email)->where('otp', '=', $otp)->count();

        if($count == 1){
            // otp update
            User::where('email', '=', $email)->update(['otp' => '1']);

            // Create Token for reset password
            $token = JWTToken::createTokenForPassword($email);

            return response()->json([
                'status' => 'success',
                'message' => 'OTP code matched and verified successfully',
                'token' => $token
            ])->cookie('token', $token, 60*5);
        
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong'
            ]);
        }


    }


    function resetPassword(Request $request){
        try{
            $email = $request->header('email');
            $password = $request->input('password');

            User::where('email', '=', $email)->update(['password' => $password]);

            return response()->json([
                'status' => 'success',
                'message' => 'Password reset successfully'
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => 'Password reset failed'
            ], 401);
        }
        
    }

    function logoutAction(Request $request){
        return redirect('/')->cookie('token', '', -1);
    }


    function userProfileInfo(Request $request){
        try{
            $email = $request->header('email');

            $data = User::where('email', '=', $email)->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Request successfull',
                'data' => $data
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => 'Request fail'
            ]);
        }
    }

    function userProfileInfoUpdate(Request $request){
        try{
            $email = $request->header('email');
            User::where('email', '=', $email)->update([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'password' => $request->input('password')
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Info updated successfully'
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => 'Request fail to update'
            ],401);
        }
    }



}
