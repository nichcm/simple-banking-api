<?php

namespace App\Application\UseCases\Transfer;

class TransferOutput
{
    public function __construct(
        public readonly string $originId,
        public readonly float  $originBalance,
        public readonly string $destinationId,
        public readonly float  $destinationBalance
    ) {}
}
