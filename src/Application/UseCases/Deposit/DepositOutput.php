<?php

namespace App\Application\UseCases\Deposit;

class DepositOutput
{
    public function __construct(
        public readonly string $destinationId,
        public readonly int  $destinationBalance
    ) {}
}
