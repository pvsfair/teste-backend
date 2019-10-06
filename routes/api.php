<?php

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Route;
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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/', function () {
    return response()->json(['mensagem' => 'Refund API', 'status' => 'Conectado']);
});

Route::namespace('Person')->group( function (){
    Route::apiResource('person', 'PersonController');
});

Route::namespace('Refund')->group( function (){
    Route::get('refund/report','RefundController@report')->name('refund.report');
    Route::get('refund/{person}','RefundController@show')->name('refund.show');
    Route::post('refund/{person}','RefundController@store')->name('refund.store');
    Route::apiResource('refund', 'RefundController')->except(['store','show']);
});
