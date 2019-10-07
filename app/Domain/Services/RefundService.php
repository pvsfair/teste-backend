<?php


namespace App\Domain\Services;


use App\Application\Exceptions\UpdateApprovedRefundException;
use App\Domain\Interfaces\Repositories\IPersonRepository;
use App\Domain\Interfaces\Repositories\IRefundRepository;
use App\Domain\Interfaces\Services\IRefundService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RefundService implements IRefundService
{
    protected $repository;
    protected $personRepository;

    public function __construct(IRefundRepository $refundRepository, IPersonRepository $personRepository)
    {
        $this->repository = $refundRepository;
        $this->personRepository = $personRepository;
    }

    public function getAllRefunds()
    {
        $refunds = $this->repository->getAll();
        return $this->paginate($refunds, 10);
    }

    public function getRefund($id)
    {
        $refund = $this->repository->find($id);
        if (!$refund) {
            throw new ModelNotFoundException("Refund não encontrado");
        }
        return $refund;
    }

    public function saveRefund($personId, array $data)
    {
        $person = $this->personRepository->find($personId, false);
        if (!$person) {
            throw new ModelNotFoundException("Person não encontradoo");
        }
        $this->validateData($data, $this->getStoringValidationData());
        $this->repository::storeRefundOn($person, $data);
        $person->save();
        return $this->personRepository->find($person->_id);
    }

    public function updateRefund($refundId, array $data)
    {
        $refund = $this->getRefund($refundId);
        $this->validateData($data, $this->getUpdatingValidationData());
        if($this->repository->isBlocked($refund)){
            throw new UpdateApprovedRefundException();
        }
        return $this->repository->updateRefund($refund, $data);
    }

    public function deleteRefund($refundId)
    {
        $refund = $this->getRefund($refundId);
        $this->repository->removeRefund($refund);
    }

    public function generateReport(array $data){
        $this->validateData($data, $this->getReportValidationData());
        return $this->repository->generateReport($data);
    }

    public function approveRefund($id)
    {
        $refund = $this->getRefund($id);
        $this->repository->blockRefund($refund);
        return $refund;
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

    protected function validateData(array $data, array $validationData)
    {
        $validator = Validator::make($data, $validationData);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    private function paginate($items, $perPage = 20, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
