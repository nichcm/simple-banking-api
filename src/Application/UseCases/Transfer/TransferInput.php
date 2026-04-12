<?php

namespace App\Application\UseCases\Transfer;

class TransferInput
{
    public function __construct(
        public readonly string $originId,
        public readonly string $destinationId,
        public readonly int  $amount
    ) {}
}
