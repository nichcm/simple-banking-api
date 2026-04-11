<?php

use Slim\Factory\AppFactory;
use App\Infrastructure\Container\Container;
use App\Infrastructure\Repositories\InMemoryAccountRepository;
use App\Application\UseCases\Reset\ResetUseCase;
use App\Http\Controllers\BankingController;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

$container->set(BankingController::class, function () {
    $repository = new InMemoryAccountRepository();

    return new BankingController(
        new ResetUseCase($repository),
    );
});

AppFactory::setContainer($container);
$app = AppFactory::create();

require __DIR__ . '/../routes/index.php';

$app->run();
