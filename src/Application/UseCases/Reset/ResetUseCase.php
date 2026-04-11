<?php

namespace App\Application\UseCases\Reset;

use App\Domain\Repositories\AccountRepositoryInterface;

class ResetUseCase
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository
    ) {}

    public function execute(): void
    {
        $this->accountRepository->reset();
    }
}
