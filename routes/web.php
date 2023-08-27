<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontEnd\HomeController;
use App\Http\Controllers\FrontEnd\VoucherController;
use App\Http\Controllers\FrontEnd\AuthController;
use App\Http\Controllers\FrontEnd\RedeemController;
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
    return redirect(route('login'));
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/home', [HomeController::class, 'purchase'])->name('purchase');

Route::get('/redeem', [RedeemController::class, 'index'])->name('redeempage');
Route::post('/redeem', [RedeemController::class, 'redeem'])->name('redeem');
Route::post('/generateVoucherCode', [RedeemController::class, 'generateVoucherCode'])->name('generateVoucherCode');


Route::get('/voucher', [VoucherController::class, 'index'])->name('voucher');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
Route::post('/profile', [AuthController::class, 'profilePost'])->name('profile.post');

