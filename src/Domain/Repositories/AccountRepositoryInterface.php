<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Account;

interface AccountRepositoryInterface {
    public function findById(string $id): ?Account;
    public function save(Account $account): void;
    public function reset(): void;
    public function beginTransaction(): void;
    public function commit(): void;
}
