<?php

use App\Http\Controllers\UrlController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerifyMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('pages.landing-page');
});


// Front end Page route
Route::get('userLogin', [UserController::class, 'userLoginPage']);
Route::get('userRegister', [UserController::class, 'userRegisterPage']);
Route::get('sendOTP', [UserController::class, 'sendOTPPage']);
Route::get('verifyOTP', [UserController::class, 'verifyOTPPage']);
Route::get('passwordReset', [UserController::class, 'passwordResetPage'])->middleware([TokenVerifyMiddleware::class]);
Route::get('/dashboard', [UserController::class, 'dashboardPage'])->middleware([TokenVerifyMiddleware::class]);
Route::get('/profile', [UserController::class, 'profilePage'])->middleware([TokenVerifyMiddleware::class]);

Route::get('/url-shortener', [UrlController::class, 'urlShortenerPager'])->middleware([TokenVerifyMiddleware::class]);



// Authentication Ajax Route
Route::post('/user-register', [UserController::class, 'userRegistration']);
Route::post('/user-login', [UserController::class, 'userLoginAction']);
Route::post('/send-otp', [UserController::class, 'sendOTPCodeToEmail']);
Route::post('/verify-otp', [UserController::class, 'OTPVerification']);
Route::post('/reset-password', [UserController::class, 'resetPassword'])->middleware([TokenVerifyMiddleware::class]);
Route::get('/user-profile', [UserController::class, 'userProfileInfo'])->middleware([TokenVerifyMiddleware::class]);
Route::post('/user-profile-update', [UserController::class, 'userProfileInfoUpdate'])->middleware([TokenVerifyMiddleware::class]);

Route::get('/logout', [UserController::class, 'logoutAction']);


// Shortener URL Ajax Route
Route::post('/create-short-url', [UrlController::class, 'shortUrlAction'])->middleware([TokenVerifyMiddleware::class]);
Route::post('/short-url-delete', [UrlController::class, 'shortUrlDeleteAction'])->middleware([TokenVerifyMiddleware::class]);
Route::get('/short-url-list', [UrlController::class, 'shortURLListShow'])->middleware([TokenVerifyMiddleware::class]);
Route::post('/short-url-list-by-id', [UrlController::class, 'shortURLListShowById'])->middleware([TokenVerifyMiddleware::class]);
