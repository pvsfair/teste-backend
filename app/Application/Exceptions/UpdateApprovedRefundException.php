<?php

namespace App\Application\Exceptions;

use Exception;
use Throwable;

class UpdateApprovedRefundException extends Exception
{
    public function __construct($message = "Não é possivel alterar um refund que já foi aprovado", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
