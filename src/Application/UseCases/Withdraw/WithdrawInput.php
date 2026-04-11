<?php

namespace App\Application\UseCases\Withdraw;

class WithdrawInput
{
    public function __construct(
        public readonly string $originId,
        public readonly float  $amount
    ) {}
}
