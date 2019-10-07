<?php

namespace App\UI\Controllers\Refund;

use App\Domain\Interfaces\Repositories\IPersonRepository as PersonRepository;
use App\Domain\Interfaces\Repositories\IRefundRepository as RefundRepository;
use App\UI\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class RefundController extends Controller
{
    protected $personRepo;
    protected $refundRepo;

    public function __construct(RefundRepository $refundRepository, PersonRepository $personRepository)
    {
        $this->refundRepo = $refundRepository;
        $this->personRepo = $personRepository;
    }

    public function index(Request $request)
    {
        $refunds = $this->refundRepo->getAll();
        return $this->paginate($refunds, 10);
    }

    public function paginate($items, $perPage = 20, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function show($id)
    {
        $refund = $this->refundRepo->find($id);
        if (!$refund) {
            return response()->json([
                'message' => 'Refund não encontrado',
            ], 404);
        }
        return response()->json($refund, 200);
    }

    public function store(Request $request, $id)
    {
        $person = $this->personRepo->find($id, false);

        if (!$person) {
            return response()->json([
                'message' => 'Person não encontrado',
            ], 404);
        }

        $validator = Validator::make($request->all(), $this->refundRepo->getStoringValidationData());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $this->refundRepo::storeRefundOn($person, $request->all());
        $person->save();
        return response()->json($person, 201);
    }

    public function update(Request $request, $id)
    {
        $refund = $this->refundRepo->find($id);
        if (!$refund) {
            return response()->json([
                'message' => 'Refund não encontrado',
            ], 404);
        }

        $validator = Validator::make($request->all(), $this->refundRepo->getUpdatingValidationData());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $refundUpdated = $this->refundRepo->updateRefund($refund, $request->only('value'));

        return response()->json($refundUpdated, 200);
    }

    public function destroy($id)
    {
        $refund = $this->refundRepo->find($id);
        if (!$refund) {
            return response()->json([
                'message' => 'Refund não encontrado',
            ], 404);
        }

        $this->refundRepo->removeRefund($refund);

        return response()->json([
            'message' => 'Refund ' . $id . ' removido com sucesso',
        ], 200);
    }

    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), $this->refundRepo->getReportValidationData());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $report = $this->refundRepo->generateReport($request->all());

        if ($request->has('csv')) {
            if ($request->query('delimiter')) {
                return self::getCSV($report, 'report.csv', $request->query('delimiter'));
            }
            return self::getCSV($report, 'report.csv');
        }

        return response()->json($report, 200);
    }

    private static function getCSV($object, $fileName = 'export.csv', $delimiter = ';')
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $fileName,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $objArr = get_object_vars($object);

        $callback = function () use ($delimiter, $objArr) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_keys($objArr), $delimiter);
            fputcsv($file, array_values($objArr), $delimiter);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


}
