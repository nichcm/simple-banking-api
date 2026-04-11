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
        $origin = $this->accountRepository->findById($input->originId);

        if ($origin === null) {
            throw new \RuntimeException('Origin account not found.');
        }

        $destination = $this->accountRepository->findById($input->destinationId);

        if ($destination === null) {
            $destination = new Account($input->destinationId);
        }

        $origin->withdraw($input->amount);
        $destination->deposit($input->amount);

        $this->accountRepository->save($origin);
        $this->accountRepository->save($destination);

        return new TransferOutput(
            $origin->getId(),
            $origin->getBalance(),
            $destination->getId(),
            $destination->getBalance()
        );
    }
}
