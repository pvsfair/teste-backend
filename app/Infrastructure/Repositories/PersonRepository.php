<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\IPersonRepository;
use Jenssegers\Mongodb\Eloquent\Model;

class PersonRepository extends Model implements IPersonRepository
{
    protected $table = "persons";

    protected $fillable = ['name', 'identification', 'jobRole'];

    protected $dates = ['created_at', 'updated_at'];

    public function find(int $identification)
    {
        // TODO: Implement find() method.
    }

    public function refunds()
    {
        return $this->embedsMany('App\Infrastructure\Repositories\RefundRepository');
    }

    public function getAll()
    {
        return $this::all();
    }
}
