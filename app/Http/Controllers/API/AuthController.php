<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Carbon\Carbon;
use Tymon\JWTAuth\Exceptions;
//use Illuminate\Support\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // public function __constructor(){
    //     $this->middleware('auth:api', [
    //         'except' => [
    //             'login',
    //             'register'
    //         ]
    //         ]);
    // }

    public function loginPost(Request $request){
        // try{
        //     $user = auth()->userOrFail();
        // }
        // catch(\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e){
        //     return response()->json(['error'=> $e]);
        // }
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'message'=>$validator->messages(),
            ],422);
        }
        else{
            $credentials = $request->only('username', 'password');
            //$token = Auth::attempt($credentials);
            $token = JWTAuth::attempt($credentials);
            if($token){
                return response()->json([
                    'status'=>200,
                    'message'=>"Login Succesfully",
                    // 'token'=> $token,
                ],200);            }
            else{
                return response()->json([
                    'status'=>500,
                    'message'=>"Invalid credentials",
                ],500);
            }
        }

    }

    public function registerPost(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'message'=>$validator->messages(),
            ],422);
        }
        else{
            $data['username'] = $request->username;
            $data['email'] = $request->email;
            $data['password'] = Hash::make($request->password);
            $user = User::create($data);
            $token = JWTAuth::fromUser($user);

            if($user){
                return response()->json([
                    'status'=>200,
                    'message'=>"Registered Succesfully",
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

    public function profilePost(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'username' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->id,
            'password' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'message'=>$validator->messages(),
            ],422);
        }
        else{
            $result = DB::table('users')
                        ->where('id', $request->id)  
                        ->limit(1)  
                        ->update(['username' => $request->username, 
                        'email' => $request->email, 
                        'password' => Hash::make($request->password), 
                        'updated_at' => Carbon::now()]);

            if($result){
                return response()->json([
                    'status'=>200,
                    'message'=>"Updated Succesfully",
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

    public function profile(Request $request){
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
            $user = DB::table('users')->where('id', $request->id)->first();
            if($user != null){
                return response()->json([
                    'status'=>200,
                    'user'=>$user,
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
