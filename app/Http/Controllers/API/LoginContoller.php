<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class LoginContoller extends Controller
{

    // login function
    function login(Request $request){
        // validation rules
        $validator = Validator::make($request->all(),[
            'email' => 'required|email', 
            'password' => 'required'
        ]);

        // check validation
		if($validator->fails()) {
            // return validation error
            return response()->json(['error'=>$validator->errors()], 401);
		}

        // check and login user
        if(auth()->attempt(['email'=>$request->email,'password'=>$request->password])){
            
            try{
                $user = Auth::user(); 
                // create token
                $tokenResult = $user->createToken('tasktoken');

                // return json response
                return response()->json([
                    'message'=>'Login Successfully',
                    'token_type' => 'Bearer',
                    'access_token' => $tokenResult->accessToken
                ],200);

            }catch(\Exception $e){
                return response()->json(['error'=>$e->getMessage()], 500);
            }
            
        }else{
            // return error
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

}
