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
    Route::get('person','PersonController@index');
    Route::post('person','PersonController@add');
    Route::delete('person','PersonController@remove');
    Route::put('person','PersonController@alter');
});

Route::namespace('Refund')->group( function (){
    Route::get('refund','RefundController@index');
    Route::post('refund','RefundController@add');
    Route::delete('refund','RefundController@remove');
    Route::put('refund','RefundController@alter');
});
