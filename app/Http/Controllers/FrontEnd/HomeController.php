<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;


class HomeController extends Controller
{
    public function index(){
        $products = DB::table('product')->get();    
        //$users = DB::select('select * from users');
        //$user = User::any()
        return view('frontend.index',['products'=>$products]);
    }

    public function purchase(Request $request){
        //error_log($request->productid);
        $transaction = new Transaction;
        $transaction->purchased_time = Carbon::now();
        $transaction->user_id = Auth::user()->id;
        $transaction->product_id = $request->productid;
        $transaction->save();


        // return response()->json([
        //     'status'=>200,
        //     'message'=>"Purchased Succesfully",
        // ]);
        
        return redirect(route('home'));
        
    }
}
