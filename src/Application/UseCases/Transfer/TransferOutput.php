<?php

namespace App\Application\UseCases\Transfer;

class TransferOutput
{
    public function __construct(
        public readonly string $originId,
        public readonly int  $originBalance,
        public readonly string $destinationId,
        public readonly int  $destinationBalance
    ) {}
}
