<?php

namespace App\UI\Controllers\Refund;

use App\Domain\Repositories\IRefundRepository as RefundRepository;
use App\UI\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

class RefundController extends Controller
{
    protected $refundRepo;
    public function __construct(RefundRepository $refundRepository)
    {
        $this->refundRepo = $refundRepository;
    }

    public function index(){
        return $this->refundRepo->getAll();
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
