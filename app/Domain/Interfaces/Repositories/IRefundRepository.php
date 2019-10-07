<?php


namespace App\Domain\Interfaces\Repositories;

use App\Domain\Interfaces\Repositories\IPersonRepository as Person;


interface IRefundRepository
{

    public function find($id);

    public function getAll();

    public static function storeRefundOn(Person $person, array $refundOpt);

    public function updateRefund($refund, array $all);

    public function removeRefund($refund);

    public function generateReport(array $all);

    public function blockRefund($refund);

    public function isBlocked($refund);

    public function getStoringValidationData(): array;

    public function getUpdatingValidationData(): array;

    public function getReportValidationData(): array;
}
