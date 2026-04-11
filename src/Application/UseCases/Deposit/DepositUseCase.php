<?php

namespace App\Application\UseCases\Deposit;

use App\Domain\Entities\Account;
use App\Domain\Repositories\AccountRepositoryInterface;
use App\Application\UseCases\Deposit\DepositInput;
use App\Application\UseCases\Deposit\DepositOutput;

class DepositUseCase
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository
    ) {}

    public function execute(DepositInput $input): DepositOutput
    {
        $account = $this->accountRepository->findById($input->destinationId);

        if ($account === null) {
            $account = new Account($input->destinationId);
        }

        $account->deposit($input->amount);
        $this->accountRepository->save($account);

        return new DepositOutput($account->getId(), $account->getBalance());
    }
}
