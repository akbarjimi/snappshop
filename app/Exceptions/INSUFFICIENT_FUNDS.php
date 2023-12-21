<?php

namespace App\Exceptions;

use App\Http\Resources\TransferFailed;
use Exception;

class INSUFFICIENT_FUNDS extends Exception
{
    const CODE = 'INSUFFICIENT_FUNDS';
    private $amount;

    public function __construct($amount)
    {
        $this->amount = $amount;
        parent::__construct();
    }

    public function render()
    {
        return new TransferFailed([
            'amount' => $this->amount,
            'code' => self::CODE,
        ]);
    }
}
