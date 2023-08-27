<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;


class HomeController extends Controller
{
    public function index(){
        $products = DB::table('product')->get();

        if(count($products) > 0){
            return response()->json([
                'status'=>200,
                'products'=>$products,
            ],200);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>"No Records Found",
            ],404);
        }

    }

    public function purchase(Request $request){
        //error_log($request->productid);
        $validator = Validator::make($request->all(), [
            'purchased_time' => 'required',
            'user_id' => 'required',
            'product_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'message'=>$validator->messages(),
            ],422);
        }
        else{

            $result = Transaction::create([
                'purchased_time' => $request->purchased_time,
                'user_id' => $request->user_id,
                'product_id' => $request->product_id,
            ]);

            if($result){
                return response()->json([
                    'status'=>200,
                    'message'=>"Purchased Succesfully",
                ],200);
            }
            else{
                return response()->json([
                    'status'=>500,
                    'message'=>"Somethings Went Wrong",
                ],500);
            }

        }       
    }
}
