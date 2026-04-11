<?php

use Slim\Factory\AppFactory;
use App\Infrastructure\Container\Container;
use App\Infrastructure\Repositories\InMemoryAccountRepository;
use App\Application\UseCases\Reset\ResetUseCase;
use App\Application\UseCases\Deposit\DepositUseCase;
use App\Application\UseCases\GetBalance\GetBalanceUseCase;
use App\Application\UseCases\Transfer\TransferUseCase;
use App\Application\UseCases\Withdraw\WithdrawUseCase;

use App\Http\Controllers\BankingController;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new Container();

$container->set(BankingController::class, function () {
    $repository = new InMemoryAccountRepository();

    return new BankingController(
        new ResetUseCase($repository),
        new DepositUseCase($repository),
        new GetBalanceUseCase($repository),
        new WithdrawUseCase($repository),
        new TransferUseCase($repository),
    );
});

AppFactory::setContainer($container);
$app = AppFactory::create();

require __DIR__ . '/../routes/index.php';

return $app;