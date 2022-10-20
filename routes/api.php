<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\LoginContoller;
use App\Http\Controllers\API\PurchaseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/users', function (Request $request) {
    return $request->user();
});

    

Route::post('login',[LoginContoller::class,'login']);

Route::group(['middleware'=>'auth:api'],function(){
    
    Route::post('addmoney',[PurchaseController::class,'addmoney']);
    Route::post('order',[PurchaseController::class,'order']);

});
