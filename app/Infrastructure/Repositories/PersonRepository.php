<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\IPersonRepository;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\EmbedsMany;

class PersonRepository extends Model implements IPersonRepository
{
    protected $table = "persons";

    protected $fillable = ['name', 'identification', 'jobRole'];

    protected $dates = ['created_at', 'updated_at', 'date'];

    public function find($identification)
    {
        $person = $this::where('_id', $identification)
            ->orWhere('identification', $identification)
            ->first();

        if(!$person){
            return response()->json([
                'message' => 'Não encontrado',
            ], 404);
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
            //$person->refunds = $person->refunds; //usado para converter as datas corretamente do banco, dentro de cada subobjeto da lista;
            // sem a linha acima a data é formatada assim:
            //                "date": {
            //                    "$date": {
            //                        "$numberLong": "1565613200000"
            //                    }
            //                },
        }


        return $allPersons;
    }

    private function fixDatesEmbededObjects(PersonRepository $Person)
    {
        $Person->refunds = $Person->refunds;
    }
}
