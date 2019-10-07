<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\IPersonRepository;
use App\Domain\Interfaces\Repositories\IRefundRepository;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class PersonRepository extends Model implements IPersonRepository
{
    use SoftDeletes;

    protected $table = "persons";

    protected $fillable = ['name', 'identification', 'jobRole'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $dateFormat = 'Y-m-d\TH:i:sP';

    protected $refundRepo;

    public function refunds()
    {
        return $this->embedsMany('App\Infrastructure\Repositories\RefundRepository');
    }

    public function __construct(array $attributes = [], IRefundRepository $refundRepo = null)
    {
        $this->refundRepo = $refundRepo;
        parent::__construct($attributes);
    }

    public function getAll()
    {
        $allPersons = $this::paginate(10);
        foreach ($allPersons->items() as $person) {
            $this->fixDatesEmbededObjects($person);
        }
        return $allPersons;
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
        return $person;
    }

    public function updatePerson($person, array $options)
    {
        $person->fill($options);
        $person->save();
        return $person;
    }

    public function removePerson($person)
    {
        $person->delete();
    }

    private function fixDatesEmbededObjects(PersonRepository $Person)
    {
        foreach ($Person->refunds as $r){
            if((isset($r->deleted_at) || !empty($r->deleted_at))){
                $Person->refunds()->dissociate($r);
            }
        }
    }
}
