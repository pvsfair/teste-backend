<?php

namespace App\UI\Controllers\Refund;

use App\Domain\Interfaces\Repositories\IPersonRepository;
use App\Domain\Interfaces\Repositories\IRefundRepository;
use App\Domain\Interfaces\Services\IRefundService;
use App\UI\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RefundController extends Controller
{
    protected $personRepo;
    protected $refundRepo;

    protected $service;

    public function __construct(IRefundService $refundService, IRefundRepository $refundRepository, IPersonRepository $personRepository)
    {
        $this->refundRepo = $refundRepository;
        $this->personRepo = $personRepository;

        $this->service = $refundService;
    }

    public function index()
    {
        return $this->service->getAllRefunds();
    }

    public function show($id)
    {
        try {
            $refund = $this->service->getRefund($id);
        } catch (ModelNotFoundException $ex) {
            return response()->json(["error" => $ex->getMessage()], 404);
        }
        return response()->json($refund, 200);
    }

    public function store(Request $request, $personId)
    {
        try {
            $person = $this->service->saveRefund($personId, $request->all());
        } catch (ModelNotFoundException $ex) {
            return response()->json(["error" => $ex->getMessage()], 404);
        } catch (ValidationException $ex) {
            return response()->json($ex->validator->errors(), 400);
        }
        return response()->json($person, 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $refundUpdated = $this->service->updateRefund($id, $request->only('value'));
        } catch (ModelNotFoundException $ex) {
            return response()->json(["error" => $ex->getMessage()], 404);
        } catch (ValidationException $ex) {
            return response()->json($ex->validator->errors(), 400);
        }
        return response()->json($refundUpdated, 200);
    }

    public function destroy($id)
    {
        try{
            $this->service->deleteRefund($id);
        } catch (ModelNotFoundException $ex) {
            return response()->json(["error" => $ex->getMessage()], 404);
        }
        return response()->json([
            'message' => 'Refund ' . $id . ' removido com sucesso',
        ], 200);
    }

    public function report(Request $request)
    {
        try{
            $report = $this->service->generateReport($request->all());
        } catch (ValidationException $ex) {
            return response()->json($ex->validator->errors(), 400);
        }

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
