<?php

namespace Tests\Unit\Domain;

use App\Domain\Entities\Account;
use InvalidArgumentException;
use RuntimeException;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    // --- constructor ---

    public function testNewAccountHasCorrectId(): void
    {
        $account = new Account('123');

        $this->assertSame('123', $account->getId());
    }

    public function testNewAccountHasZeroBalanceByDefault(): void
    {
        $account = new Account('123');

        $this->assertSame(0, $account->getBalance());
    }

    public function testNewAccountAcceptsInitialBalance(): void
    {
        $account = new Account('123', 50);

        $this->assertSame(50, $account->getBalance());
    }

    // --- deposit ---

    public function testDepositIncreasesBalance(): void
    {
        $account = new Account('123');
        $account->deposit(100);

        $this->assertSame(100, $account->getBalance());
    }

    public function testMultipleDepositsAccumulateBalance(): void
    {
        $account = new Account('123');
        $account->deposit(30);
        $account->deposit(20);

        $this->assertSame(50, $account->getBalance());
    }

    public function testDepositWithZeroAmountThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $account = new Account('123');
        $account->deposit(0);
    }

    public function testDepositWithNegativeAmountThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $account = new Account('123');
        $account->deposit(-10);
    }

    // --- withdraw ---

    public function testWithdrawDecreasesBalance(): void
    {
        $account = new Account('123', 100.0);
        $account->withdraw(40);

        $this->assertSame(60, $account->getBalance());
    }

    public function testWithdrawEntireBalanceResultsInZero(): void
    {
        $account = new Account('123', 100);
        $account->withdraw(100);

        $this->assertSame(0, $account->getBalance());
    }

    public function testWithdrawMoreThanBalanceThrowsException(): void
    {
        $this->expectException(RuntimeException::class);

        $account = new Account('123', 50);
        $account->withdraw(100);
    }

    public function testWithdrawWithZeroAmountThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $account = new Account('123', 50);
        $account->withdraw(0);
    }

    public function testWithdrawWithNegativeAmountThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $account = new Account('123', 50);
        $account->withdraw(-10);
    }
}
