<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\AccountRepositoryInterface;
use App\Domain\Entities\Account;

class InMemoryAccountRepository implements AccountRepositoryInterface
{
    private const STORAGE_KEY = 'banking_accounts';

    public function findById(string $id): ?Account
    {
        /** @phpstan-ignore-next-line */
        $data = apcu_fetch(self::STORAGE_KEY);

        if ($data === false || !isset($data[$id])) {
            return null;
        }

        return new Account($data[$id]['id'], $data[$id]['balance']);
    }

    public function save(Account $account): void
    {
        /** @phpstan-ignore-next-line */
        $data = apcu_fetch(self::STORAGE_KEY);

        if ($data === false) {
            $data = [];
        }

        $data[$account->getId()] = [
            'id'      => $account->getId(),
            'balance' => $account->getBalance(),
        ];

        apcu_store(self::STORAGE_KEY, $data);
    }

    public function reset(): void
    {
        /** @phpstan-ignore-next-line */
        apcu_store(self::STORAGE_KEY, []);
    }
}
