<?php

namespace App\Application\UseCases\GetBalance;

class GetBalanceOutput
{
    public function __construct(
        public readonly float $balance
    ) {}
}
