<?php

namespace App\Domain\Entities;
use InvalidArgumentException;
use RuntimeException;

class Account {
    private string $id;
    private int $balance;

    public function __construct(string $id, int $balance = 0) {
        $this->id = $id;
        $this->balance = $balance;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getBalance(): int {
        return $this->balance;
    }

    public function deposit(int $amount): void {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Deposit amount must be positive.");
        }
        $this->balance += $amount;
    }

    public function withdraw(int $amount): void {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Withdrawal amount must be positive.");
        }
        if ($amount > $this->balance) {
            throw new RuntimeException("Insufficient funds.");
        }
        $this->balance -= $amount;
    }
}