<?php

namespace App\UI\Controllers\Person;

use App\Domain\Interfaces\Repositories\IPersonRepository;
use App\Domain\Interfaces\Services\IPersonService;
use App\UI\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PersonController extends Controller
{
    protected $service;

    public function __construct(IPersonService $personService)
    {
        $this->service = $personService;
    }

    public function index()
    {
        return $this->service->getAllPersons();
    }

    public function show($id)
    {
        try {
            $person = $this->service->getPerson($id);
        }catch (ModelNotFoundException $ex){
            return response()->json(["error"=>$ex->getMessage()],404);
        }
        return response()->json($person);
    }

    public function store(Request $request)
    {
        try {
            $personStored = $this->service->savePerson($request->all());
        } catch (ValidationException $ex) {
            return response()->json($ex->validator->errors(), 400);
        }
        return response()->json($personStored, 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $personUpdated = $this->service->updatePerson($id, $request->all());
        }catch (ModelNotFoundException $ex){
            return response()->json(["error"=>$ex->getMessage()],404);
        }catch (ValidationException $ex){
            return response()->json($ex->validator->errors(), 400);
        }
        return response()->json($personUpdated, 200);
    }

    public function destroy($id)
    {
        try{
            $this->service->deletePerson($id);
        }catch (ModelNotFoundException $ex){
            return response()->json(["error"=>$ex->getMessage()],404);
        }

        return response()->json([
            'message' => 'Person ' . $id . ' removido com sucesso',
        ], 200);
    }
}

