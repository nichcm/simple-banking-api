<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BankingController
{
    public function __construct(
    ) {}

    public function ping(Request $request, Response $response, array $args): Response
    {
        $response->getBody()->write('pong');
        return $response;
    }

    public function reset(Request $request, Response $response, array $args): Response
    {
        $response->getBody()->write('OK');
        return $response->withStatus(200);
    }

    public function balance(Request $request, Response $response, array $args): Response
    {
        $response->getBody()->write('OK');
        return $response->withStatus(200);
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
        $response->getBody()->write('OK');
        return $response->withStatus(200);
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
