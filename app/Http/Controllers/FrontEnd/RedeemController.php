<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Carbon\Carbon;
use thiagoalessio\TesseractOCR\TesseractOCR;

class RedeemController extends Controller
{
    function clearLimiter()
    {     
        RateLimiter::clear('redeem-voucher');     
        return redirect()->back();
    }

    public function index(){

        $event = DB::table('event')->first();
        //check user is redeemed
        $redeemedByUser = DB::table('voucher')->where('redempt_by', Auth::user()->id)->where('event_id', $event->id)->first();
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
            ->where('user_id', Auth::user()->id)
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
        error_log($availableVoucher);

        return View('frontend.redeem',['event'=>$event, 'isRedeemed'=>$isRedeemed, 'availableVouchers'=>$availableVouchers, 'term'=>$term]);
    }

    public function redeem(Request $request){
        $executed = RateLimiter::attempt(
            'redeem-voucher',
            $perSecond = 60,
            function() {
                //dd(RateLimiter::availableIn('redeem-voucher'));
            },
        );
         
        if (! $executed) {
            // sleep(5);
            // $this->clearLimiter();
            return redirect(route('redeem'))->with("error", "Too many request. Please try again");
        }

        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg',
        ]);
        
        $photo_name = $request->file('image')->getClientOriginalName();
        $request->image->move(public_path('frontend\\images\\'), $photo_name);
        $photo_path = public_path('frontend\\images\\').$photo_name;        
        $tesseractOcr = new TesseractOCR();
        $tesseractOcr->image(public_path('frontend\\images\\').$photo_name);
        $text = $tesseractOcr->run();
        //error_log($text);
        
        if(str_contains(strtolower($text), "product abc")){

            //check total voucher
            $availableVoucher = DB::table('voucher')->where('redempt_by', '=' , null)->where('event_id', $request->eventid)->count();
            $availableVouchers = false;
            if($availableVoucher > 0){
                $availableVouchers = true;
            }

            if($availableVouchers){
                DB::table('voucher')
                    ->where('redempt_by', null)
                    ->limit(1)
                    ->update(['redempt_by' => Auth::user()->id,
                            'photo_name' => $photo_name,
                            'photo_path' => $photo_path,
                            'updated_at' => Carbon::now(),
                    ]);
                
                return redirect(route('voucher'))->with("success", "Redeemed Succesfully.");
            }
            else{
                return redirect(route('redeem'))->with("error", "Out Of Stock");
            }
    
        }
        else{
            return redirect(route('redeem'))->with("error", "Photo is not valid. Please resubmit the photo.");
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
                                    'redempt_by' => 1,
                                    'event_id' => $event->id,
                                    'photo_name' => null,
                                    'photo_path' => null,
                                    );
                    DB::table('voucher')->insert($values);
                }
            }
            return redirect(route('redeem'));
        }
        else{
            return redirect(route('redeem'))->with("error", "No Records Found.");;
        }
    }

    function generateRandomString($length = 10) {
        $randomString = md5(uniqid(rand(), true));
        $randomString = substr($randomString, 0, $length);
     
        return $randomString;
     }
     
}
