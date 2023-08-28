<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\VoucherController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RedeemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('home', [HomeController::class, 'index']);
Route::post('purchase', [HomeController::class, 'purchase']);

Route::get('redeempage', [RedeemController::class, 'index']);
Route::post('redeem', [RedeemController::class, 'redeem']);
Route::post('generateVoucherCode', [RedeemController::class, 'generateVoucherCode']);

Route::get('voucher', [VoucherController::class, 'index']);

Route::post('login', [AuthController::class, 'loginPost']);
Route::post('register', [AuthController::class, 'registerPost']);
Route::get('profile', [AuthController::class, 'profile']);
Route::post('profile', [AuthController::class, 'profilePost']);