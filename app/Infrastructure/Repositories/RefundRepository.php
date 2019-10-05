<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\IPersonRepository as IPerson;
use App\Domain\Repositories\IRefundRepository;
use Jenssegers\Mongodb\Eloquent\Model;

class RefundRepository extends Model implements IRefundRepository
{
    protected $table = "refunds"; // não se aplica ao mongodb, pq ficará dentro do arquivo de persons

    protected $fillable = ['type', 'description', 'value'];

    protected $dates = ['date', 'created_at', 'updated_at'];


    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function create(IPerson $person)
    {
        // TODO: Implement create() method.
    }
}
