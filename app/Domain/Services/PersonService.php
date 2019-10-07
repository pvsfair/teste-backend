<?php


namespace App\Domain\Services;


use App\Domain\Interfaces\Repositories\IPersonRepository;
use App\Domain\Interfaces\Services\IPersonService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PersonService implements IPersonService
{
    private $personRepo;

    public function __construct(IPersonRepository $personRepo)
    {
        $this->personRepo = $personRepo;
    }

    public function getAllPersons()
    {
        return $this->personRepo->getAll();
    }

    public function getPerson($id)
    {
        $person = $this->personRepo->find($id);
        if(!$person){
            throw new ModelNotFoundException("Person não encontrado");
        }
        return $person;
    }

    public function savePerson(array $data)
    {
        $this->validateData($data);

        return $this->personRepo->storePerson($data);
    }

    public function updatePerson($personId, array $data)
    {
        $person = $this->getPerson($personId);

        $this->validateData($data, false);

        return $this->personRepo->updatePerson($person, $data);
    }

    public function deletePerson($personId)
    {
        $person = $this->getPerson($personId);

        return $this->personRepo->removePerson($person);
    }

    protected function validateData(array $data, $isStore = true)
    {
        if($isStore){
            $validationData = $this->getStoringValidationData();
        }else{
            $validationData = $this->getUpdatingValidationData();
        }

        $validator = Validator::make($data, $validationData);
        if($validator->fails()){
            throw new ValidationException($validator);
        }
    }

    public function getStoringValidationData(): array
    {
        return ['name' => 'required|string',
            'identification' => 'required|string|unique:persons|max:11',
            'jobRole' => 'required|string',
            'refunds' => 'array|nullable'];
    }

    public function getUpdatingValidationData(): array
    {
        return ['name' => 'filled',
            'identification' => 'filled|unique:persons|max:11',
            'jobRole' => 'filled'];
    }
}
