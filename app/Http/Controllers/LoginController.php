<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    { 
        $validator = Validator::make($request->all(),[

            'email' => 'required|email|string',
            'password' => 'required|string|min:6'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(),400);
        }
        if(!$token = auth()->attempt($validator->validated()))
        {
            return response()->json(['error'=>'Unauthorized']);
        }
        return $this->respondWithToken($token);
    }
    
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token'=>$token,
            'token_type'=>'bearer',
            'expires_in'=>auth()->factory()->getTTL()*60
        ]);
    }
}
