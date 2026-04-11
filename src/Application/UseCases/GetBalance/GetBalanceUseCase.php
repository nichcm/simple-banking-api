<?php

namespace App\Application\UseCases\GetBalance;

use App\Domain\Repositories\AccountRepositoryInterface;

class GetBalanceUseCase
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository
    ) {}

    public function execute(GetBalanceInput $input): GetBalanceOutput
    {
        $account = $this->accountRepository->findById($input->accountId);

        if ($account === null) {
            throw new \RuntimeException('Account not found.');
        }

        return new GetBalanceOutput($account->getBalance());
    }
}
