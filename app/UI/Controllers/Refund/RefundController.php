<?php

namespace App\UI\Controllers\Refund;

use App\UI\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

class RefundController extends Controller
{
    public function index(){
        return response()->json(['refund'=>'not connected to the DB']);
    }
    public function add(){
        return response()->json(['refund'=>'not connected to the DB']);
    }
    public function remove(){
        return response()->json(['refund'=>'not connected to the DB']);
    }
    public function alter(){
        return response()->json(['refund'=>'not connected to the DB']);
    }
}
