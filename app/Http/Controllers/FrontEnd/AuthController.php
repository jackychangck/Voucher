<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login(){
        if(Auth::check()){
            return redirect(route('home'));
        }
        return View('frontend.login');
    }

    public function register(){
        if(Auth::check()){
            return redirect(route('home'));
        }
        return View('frontend.register');
    }

    public function loginPost(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('username', 'password');
        if(Auth::attempt($credentials)){
            return redirect()->route('home');
        }
        return redirect(route('login'))->with("error", "Invalid Login Details");

    }

    public function registerPost(Request $request){
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $data['username'] = $request->username;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);

        if(!$user){
            return redirect(route('register'))->with("error", "Registration failed. Please try agian.");
        }
        return redirect(route('login'))->with("success", "Registration success. Login to access the application.");

    }

    public function profilePost(Request $request){
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id,
            'password' => 'required'
        ]);

        $result = DB::table('users')
                    ->where('id', Auth::user()->id)  
                    ->limit(1)  
                    ->update(['username' => $request->username, 
                    'email' => $request->email, 
                    'password' => Hash::make($request->password), 
                    'updated_at' => Carbon::now()]);

        if(!$result){
            return redirect(route('profile'))->with("error", "Update failed. Please try agian.");
            //return redirect(route('profile'))->with("error", $result);
        }
        return redirect(route('profile'))->with("success", "Update successfully.");

    }

    public function profile(){
        $user = DB::table('users')->where('id', Auth::user()->id)->first();    
        //$users = DB::select('select * from users');
        //$user = User::any()
        return view('frontend.profile',['user'=>$user]);
    }

    public function logout(){
        Session::flush();
        Auth::logout();
        return redirect(route('login'));
    }
}
