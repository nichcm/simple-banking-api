<?php

namespace App\Application\UseCases\GetBalance;

class GetBalanceInput
{
    public function __construct(
        public readonly string $accountId
    ) {}
}
