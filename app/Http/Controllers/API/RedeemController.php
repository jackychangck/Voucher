<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use thiagoalessio\TesseractOCR\TesseractOCR;

class RedeemController extends Controller
{
    function clearLimiter()
    {     
        RateLimiter::clear('redeem-voucher');     
        return redirect()->back();
    }

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
            $event = DB::table('event')->first();
            
            //check user is redeemed
            $redeemedByUser = DB::table('voucher')->where('redempt_by', $request->id)->where('event_id', $event->id)->first();
            $isRedeemed = false;
            if($redeemedByUser != null){
                $isRedeemed = true;
            }
            //check total voucher
            $availableVoucher = DB::table('voucher')->where('redempt_by', '=' , null)->where('event_id', $event->id)->count();
            $availableVouchers = false;
            if($availableVoucher > 0){
                $availableVouchers = true;
            }
            //error_log($vouchers->where('event_id', $event->id)->count());

            //check transaction >= 100, last 30days, 3 transactions
            $voucherList = DB::table('transaction')
                ->join('product', 'transaction.product_id', '=', 'product.id')
                ->select('transaction.*', 'product.*')
                ->where('user_id', $request->id)
                ->where('purchased_time', '>=', Carbon::now()->addDays(-30))
                ->get();
            $totalprice = 0;
            foreach($voucherList as $voucherLists){
                $totalprice += $voucherLists->price;
            }
            $term = false;
            if($totalprice >= 100 && count($voucherList) >= 3){
                $term = true;
            }
            if($event != null){
                return response()->json([
                    'status'=>200,
                    'event'=>$event,
                    'isRedeemed'=>$isRedeemed,
                    'availableVouchers'=>$availableVouchers,
                    'term'=>$term,
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

    public function redeem(Request $request){
        $executed = RateLimiter::attempt(
            'redeem-voucher',
            $perSecond = 60,
            function() {
            },
        );
         
        if (! $executed) {
            return response()->json([
                'status'=>429,
                'message'=>"Too Many Requests. Please try again",
            ],429);        
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'event_id' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'message'=>$validator->messages(),
            ],422);
        }
        else{
            $photo_name = $request->file('image')->getClientOriginalName();
            $photo_path = $request->file('image')->store('public/frontend/images');        
            $tesseractOcr = new TesseractOCR();
            $tesseractOcr->image(public_path('frontend\\images\\').$photo_name);
            $text = $tesseractOcr->run();
            //error_log($text);
            
            if(str_contains(strtolower($text), "product abc")){

                //check total voucher
                $availableVoucher = DB::table('voucher')->where('redempt_by', '=' , null)->where('event_id', $request->event_id)->count();
                $availableVouchers = false;
                if($availableVoucher > 0){
                    $availableVouchers = true;
                }

                if($availableVouchers){
                    $result = DB::table('voucher')
                    ->where('redempt_by', null)
                    ->limit(1)
                    ->update(['redempt_by' => $request->id,
                            'photo_name' => $photo_name,
                            'photo_path' => $photo_path,
                            'updated_at' => Carbon::now(),
                    ]);
                
                    if($result){
                        return response()->json([
                            'status'=>200,
                            'message'=>"Redeemed Succesfully",
                        ],200);
                    }
                    else{
                        return response()->json([
                            'status'=>500,
                            'message'=>"Somethings Went Wrong",
                        ],500);
                    }
                }
                else{
                    return response()->json([
                        'status'=>422,
                        'message'=>"Out Of Stock",
                    ],422);
                }
        
            }
            else{
                return response()->json([
                    'status'=>422,
                    'message'=>"Photo is not valid. Please resubmit the photo.",
                ],422);
            }
        }
    }

    public function generateVoucherCode(){
        $events = DB::table('event')->get();
        if(count($events) > 0){
            foreach($events as $event){
                for($i = 0; $i< $event->total_voucher; $i++){
                    $randomString = $this->generateRandomString(10);
                    $randomString = "FT".$randomString;
                    $values = array('voucher_code' => $randomString, 
                                    'expiration_date' => $event->end_time,
                                    'redempt_by' => NULL,
                                    'event_id' => $event->id,
                                    'photo_name' => null,
                                    'photo_path' => null,
                                    );
                    DB::table('voucher')->insert($values);
                }
            }
            return response()->json([
                'status'=>200,
                'message'=>"Generated Succesfully",
            ],200);        
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>"No Records Found",
            ],404);
        }
    }


    function generateRandomString($length = 10) {
        $randomString = md5(uniqid(rand(), true));
        $randomString = substr($randomString, 0, $length);
     
        return $randomString;
     }
     
}
