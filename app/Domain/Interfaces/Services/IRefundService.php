<?php


namespace App\Domain\Interfaces\Services;


interface IRefundService
{
    public function getAllRefunds();

    public function getRefund($id);

    public function saveRefund($personId, array $data);

    public function updateRefund($refundId, array $data);

    public function deleteRefund($refundId);

    public function generateReport(array $data);

    public function approveRefund($id);

    public function getStoringValidationData(): array;

    public function getUpdatingValidationData(): array;

    public function getReportValidationData(): array;
}
