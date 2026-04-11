<?php

namespace App\Domain\Entities;
use InvalidArgumentException;
use RuntimeException;

class Account {
    private string $id;
    private float $balance;

    public function __construct(string $id, float $balance = 0.0) {
        $this->id = $id;
        $this->balance = $balance;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getBalance(): float {
        return $this->balance;
    }

    public function deposit(float $amount): void {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Deposit amount must be positive.");
        }
        $this->balance += $amount;
    }

    public function withdraw(float $amount): void {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Withdrawal amount must be positive.");
        }
        if ($amount > $this->balance) {
            throw new RuntimeException("Insufficient funds.");
        }
        $this->balance -= $amount;
    }
}