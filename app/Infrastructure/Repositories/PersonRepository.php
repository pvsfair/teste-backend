<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\IPersonRepository;
use App\Domain\Repositories\IRefundRepository;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\EmbedsMany;

class PersonRepository extends Model implements IPersonRepository
{
    protected $table = "persons";

    protected $fillable = ['name', 'identification', 'jobRole'];

    protected $dates = ['created_at', 'updated_at'];

    protected $dateFormat = 'Y-m-d\TH:i:sP';

    protected $refundRepo;

    public function __construct(array $attributes = [], IRefundRepository $refundRepo = null)
    {
        $this->refundRepo = $refundRepo;
        parent::__construct($attributes);
    }

    public function find($identification, $fixToShow = true)
    {
        $person = parent::find($identification);

        if (!$person) {
            $person = $this::where('identification', $identification)->first();
        }
        if ($person && $fixToShow) {
            $this->fixDatesEmbededObjects($person);
        }
        return $person;
    }

    public function refunds()
    {
        return $this->embedsMany('App\Infrastructure\Repositories\RefundRepository');
    }

    public function getAll()
    {
        $allPersons = $this::all();
        foreach ($allPersons as $person) {
            $this->fixDatesEmbededObjects($person);
        }
        return $allPersons;
    }

    public function storePerson(array $options)
    {
        $person = new PersonRepository();
        $person->fill($options);
        if ($options['refunds']) {
            foreach ($options['refunds'] as $refundOpt) {
                RefundRepository::storeRefundOn($person, $refundOpt);
            }
        }

        $person->save();
        return response()->json($person, 201);
    }

    public function updatePerson($person, array $options)
    {
        $person->fill($options);
        echo $person;
        echo $person->refunds;

//        foreach ($options['refunds'] as $refundOpt) {
//            RefundRepository::storeRefundOn($person, $refundOpt);
//        }

        $person->save();
        return response()->json($person, 200);
    }

    public function removePerson($person)
    {
        $person->delete();
    }

    private function fixDatesEmbededObjects(PersonRepository $Person)
    {
        $Person->refunds = $Person->refunds; //usado para converter as datas corretamente do banco, dentro de cada subobjeto da lista;
        // sem a linha acima a data é formatada assim:
        //                "date": {
        //                    "$date": {
        //                        "$numberLong": "1565613200000"
        //                    }
        //                },
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
