<?php

namespace App\Application\UseCases\Withdraw;

use App\Domain\Repositories\AccountRepositoryInterface;

class WithdrawUseCase
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository
    ) {}

    public function execute(WithdrawInput $input): WithdrawOutput
    {
        $account = $this->accountRepository->findById($input->originId);

        if ($account === null) {
            throw new \RuntimeException('Account not found.');
        }

        $account->withdraw($input->amount);
        $this->accountRepository->save($account);

        return new WithdrawOutput($account->getId(), $account->getBalance());
    }
}
