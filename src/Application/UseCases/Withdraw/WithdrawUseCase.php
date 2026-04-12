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
        $this->accountRepository->beginTransaction();

        $account = $this->accountRepository->findById($input->originId);

        if ($account === null) {
            $this->accountRepository->commit();
            throw new \RuntimeException('Account not found.');
        }

        $account->withdraw($input->amount);
        $this->accountRepository->save($account);

        $this->accountRepository->commit();

        return new WithdrawOutput($account->getId(), $account->getBalance());
    }
}
