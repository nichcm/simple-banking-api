<?php

namespace App\Application\UseCases\Transfer;

use App\Domain\Entities\Account;
use App\Domain\Repositories\AccountRepositoryInterface;

class TransferUseCase
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository
    ) {}

    public function execute(TransferInput $input): TransferOutput
    {
        $this->accountRepository->beginTransaction();

        $origin = $this->accountRepository->findById($input->originId);

        if ($origin === null) {
            $this->accountRepository->commit();
            throw new \RuntimeException('Origin account not found.');
        }

        if ($input->originId === $input->destinationId) {
            $this->accountRepository->commit();
            throw new \RuntimeException('Origin and destination accounts must be different.');
        }

        $destination = $this->accountRepository->findById($input->destinationId);

        if ($destination === null) {
            $destination = new Account($input->destinationId);
        }

        $origin->withdraw($input->amount);
        $destination->deposit($input->amount);

        $this->accountRepository->save($origin);
        $this->accountRepository->save($destination);

        $this->accountRepository->commit();

        return new TransferOutput(
            $origin->getId(),
            $origin->getBalance(),
            $destination->getId(),
            $destination->getBalance()
        );
    }
}
