<?php

namespace App\Application\UseCases\Withdraw;

class WithdrawOutput
{
    public function __construct(
        public readonly string $originId,
        public readonly float  $originBalance
    ) {}
}
