<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VoucherController extends Controller
{
    public function index(){
        $vouchers = DB::table('voucher')->where('redempt_by', Auth::user()->id)->get();    
        return View('frontend.voucher',['vouchers'=>$vouchers]);
    }


}
