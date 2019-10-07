<?php

namespace App\UI\Controllers\Person;

use App\Domain\Interfaces\Repositories\IPersonRepository as PersonRepository;
use App\UI\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonController extends Controller
{
    protected $personRepo;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepo = $personRepository;
    }

    public function index()
    {
        return $this->personRepo->getAll();
    }

    public function show($id)
    {
        $person = $this->personRepo->find($id);
        if (!$person) {
            return response()->json([
                'message' => 'Person não encontrado',
            ], 404);
        }
        return $person;
    }

    public function store(Request $request)
    {
        //move to service
        $validator = Validator::make($request->all(), $this->personRepo->getStoringValidationData());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        return $this->personRepo->storePerson($request->all());
    }

    public function update(Request $request, $id)
    {
        $person = $this->personRepo->find($id, false);
        if (!$person) {
            return response()->json([
                'message' => 'Person não encontrado',
            ], 404);
        }
        //move to service
        $validator = Validator::make($request->all(), $this->personRepo->getUpdatingValidationData());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        return $this->personRepo->updatePerson($person, $request->all());
    }

    public function destroy($id)
    {
        $person = $this->personRepo->find($id, false);
        if (!$person) {
            return response()->json([
                'message' => 'Person não encontrado',
            ], 404);
        }

        $this->personRepo->removePerson($person);
        return response()->json([
            'message' => 'Person ' . $id . ' removido com sucesso',
        ], 200);
    }
}

