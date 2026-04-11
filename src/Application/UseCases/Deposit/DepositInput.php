<?php

namespace App\Application\UseCases\Deposit;

class DepositInput
{
    public function __construct(
        public readonly string $destinationId,
        public readonly float  $amount
    ) {}
}
