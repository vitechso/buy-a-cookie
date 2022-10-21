<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\OrderModel;
use Illuminate\Support\Facades\Gate;

class PurchaseController extends Controller
{

    // add money to wallet
    function addmoney(Request $request){
        // validation rules
        $validator = Validator::make($request->all(),[
            'money' => 'required|numeric|min:3|max:100'
        ]);

        // check validation
        if($validator->fails()) {
            // return validation error
            return response()->json(['error'=>$validator->errors()], 400);
        }

        try{
            
            $request->user()->wallet = $request->user()->wallet+$request->money;
            $request->user()->save();

            return response()->json([
                'status'=>200,
                'message'=>'Successfully added',
                'data'=>['wallet_balance'=>round($request->user()->wallet,2)]
            ], 200);
            
        }catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage()], 500);
        }
    }

    // purchase cookies

    function order(Request $request){

        $rate = 1;
        $user_id = $request->user()->id;
        $wallet_balance = $request->user()->wallet;
                
        // validation rules
        $validator = Validator::make($request->all(),[
            'qty' => 'required|integer|gte:1|lte:'.($wallet_balance/$rate),
        ]);

        // check validation
        if($validator->fails()) {
            // return validation error
            return response()->json(['error'=>$validator->errors()], 400);
        }

        try{
            $order_amount = $request->qty*$rate;
            $order_response = OrderModel::create([
                'user_id'=>$user_id,
                'qty'=>$request->qty,
                'amount'=>$order_amount
            ]);

            if($order_response){
                // check auth user and order purchase user
                if (!Gate::allows('update-wallet',$order_response)) {
                    return response()->json(['message'=>'Forbidden'], 403);
                }
                
                $request->user()->wallet = ($request->user()->wallet - $order_amount);
                $request->user()->save();
            }

            return response()->json(['message'=>'Order purchase successfully'], 200);
        }catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage()], 500);
        }
    }
    
}
