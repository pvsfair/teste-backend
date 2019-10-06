<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\IPersonRepository as IPerson;
use App\Domain\Repositories\IRefundRepository;
use App\Infrastructure\Repositories\PersonRepository as Person;
use Illuminate\Support\Facades\DB;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use MongoDB\BSON\ObjectId;
use stdClass;

class RefundRepository extends Model implements IRefundRepository
{
    use SoftDeletes;

    protected $table = "refunds"; // não se aplica ao mongodb, pq ficará dentro do arquivo de persons

    protected $fillable = ['type', 'description', 'value', 'date'];

    protected $dates = ['date', 'created_at', 'updated_at', 'deleted_at'];

    protected $dateFormat = 'Y-m-d\TH:i:sP';

    protected $ensureNumber = ['value'];

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->ensureNumber) && $value) {
            $value = doubleval($value);
        }
        return parent::setAttribute($key, $value); // TODO: Change the autogenerated stub
    }

    public function find($id)
    {
        //return Person::whereRaw(['refunds._id'=>new ObjectId($id)])->first()->refunds;

        return collect($this->getAll())->first(function ($value, $key) use ($id) {
//            echo $value;
//            var_dump(property_exists($value, 'deleted_at'));
//            var_dump(isset($value->deleted_at));
//            var_dump(empty($value->deleted_at));
            return $value->_id == $id && (!isset($value->deleted_at) || empty($value->deleted_at));
        });
    }

    public function getAll()
    {
        $refunds = Person::all()->pluck('refunds');
        $allRefunds = collect();
        foreach ($refunds as $refund) {
            $allRefunds = $allRefunds->merge($refund);
        }
        return $allRefunds->all();
    }

    public static function storeRefundOn(IPerson $person, array $refundOpt)
    {
        $refund = new RefundRepository();
        $refund->fill($refundOpt);
        if ($person instanceof Model) {
            $person->refunds()->associate($refund);
        }
        return $refund;
    }

    public function updateRefund($refund, array $options)
    {
        $refund->fill($options);
        $refund->save();
    }

    public function removeRefund($refund)
    {
        $refund->delete();
    }

    public function generateReport(array $options)
    {
        $return = new stdClass();

        $return->month = $options['month'];
        $return->year = $options['year'];
        //echo $month .' '.$year;

        $allRefunds = $this->getAll();


        $return->totalRefunds = 0;
        $return->refunds = 0;

        foreach ($allRefunds as $refund) {
            $date = date_parse($refund->date);
            if ($date['year'] == $return->year && $date['month'] == $return->month) {
                $return->refunds++;
                $return->totalRefunds += $refund->value;
            }
        }
        $return->totalRefunds = round($return->totalRefunds, 2);

        return $return;
    }

    public function getStoringValidationData(): array
    {
        return [
            'date' => 'required|date|date_format:Y-m-d\TH:i:sP',
            'type' => 'required|string',
            'description' => 'required|string',
            'value' => 'required|numeric',
        ];
    }

    public function getUpdatingValidationData(): array
    {
        return [
            'value' => 'required|numeric',
        ];
    }

    public function getReportValidationData(): array
    {
        return [
            'month' => 'required|integer',
            'year' => 'required|integer',
        ];
    }
}
