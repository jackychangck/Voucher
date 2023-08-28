<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VoucherController extends Controller
{

    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'message'=>$validator->messages(),
            ],422);
        }
        else{
            $vouchers = DB::table('voucher')->where('redempt_by', $request->id)->get();    

            if(count($vouchers) > 0){
                return response()->json([
                    'status'=>200,
                    'vouchers'=>$vouchers,
                ],200);
            }
            else{
                return response()->json([
                    'status'=>404,
                    'message'=>"No Records Found",
                ],404);
            }
        }

    }


}
