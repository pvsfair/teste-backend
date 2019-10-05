<?php

namespace App\UI\Controllers\Person;

use App\Domain\Repositories\IPersonRepository as PersonRepository;
use App\UI\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

class PersonController extends Controller
{
    private $person;

    public function __construct(PersonRepository $person)
    {
        $this->person = $person;
    }

    public function index(){
        return $this->person->getAll();
//        return response()->json(['person'=>'not connected to the DB']);
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
