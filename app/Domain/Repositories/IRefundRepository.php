<?php


namespace App\Domain\Repositories;

use App\Domain\Repositories\IPersonRepository as Person;


interface IRefundRepository
{

    public function find($id);

    public function getAll();

    public static function storeRefundOn(Person $person, array $refundOpt);

    public function updateRefund($refund, array $all);

    public function removeRefund($refund);

    public function generateReport(array $all);

    public function getStoringValidationData(): array;

    public function getUpdatingValidationData(): array;

    public function getReportValidationData(): array;
}
