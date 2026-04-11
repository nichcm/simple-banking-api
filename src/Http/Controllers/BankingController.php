<?php

namespace App\Http\Controllers;

use App\Application\UseCases\Reset\ResetUseCase;
use App\Application\UseCases\Deposit\DepositUseCase;
use App\Application\UseCases\Deposit\DepositInput;
use App\Application\UseCases\GetBalance\GetBalanceUseCase;
use App\Application\UseCases\GetBalance\GetBalanceInput;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BankingController
{
    public function __construct(
        private ResetUseCase    $resetUseCase,
        private DepositUseCase  $depositUseCase,
        private GetBalanceUseCase  $getBalanceUseCase,
    ) {}

    public function ping(Request $request, Response $response, array $args): Response
    {
        $response->getBody()->write('pong');
        return $response;
    }

    public function reset(Request $request, Response $response, array $args): Response
    {
        $this->resetUseCase->execute();
        $response->getBody()->write('OK');
        return $response->withStatus(200);
    }

    public function balance(Request $request, Response $response, array $args): Response
    {
        $params    = $request->getQueryParams();
        $accountId = $params['account_id'] ?? null;

        if ($accountId === null) {
            return $response->withStatus(400);
        }

        try {
            $output = $this->getBalanceUseCase->execute(new GetBalanceInput($accountId));
            $response->getBody()->write((string) $output->balance);
            return $response->withStatus(200);
        } catch (\RuntimeException) {
            return $response->withStatus(404);
        }
    }

    public function event(Request $request, Response $response, array $args): Response
    {
        $body = json_decode((string) $request->getBody(), true);
        $type = $body['type'] ?? null;

        try {
            return match ($type) {
                'deposit'  => $this->handleDeposit($body, $response),
                'withdraw' => $this->handleWithdraw($body, $response),
                'transfer' => $this->handleTransfer($body, $response),
                default    => $response->withStatus(400),
            };
        } catch (\RuntimeException) {
            return $response->withStatus(404);
        }
    }

    private function handleDeposit(array $body, Response $response): Response
    {
        $output = $this->depositUseCase->execute(
            new DepositInput($body['destination'], (float) $body['amount'])
        );

        $response->getBody()->write(json_encode([
            'destination' => ['id' => $output->destinationId, 'balance' => $output->destinationBalance],
        ]));

        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    private function handleWithdraw(array $body, Response $response): Response
    {
        $response->getBody()->write('OK');
        return $response->withStatus(200);
    }

    private function handleTransfer(array $body, Response $response): Response
    {
        $response->getBody()->write('OK');
        return $response->withStatus(200);
    }
}
