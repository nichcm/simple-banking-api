<?php

namespace Tests\Integration;

use BcMath\Number;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;

class BankingApiTest extends TestCase
{
    private App $app;

    protected function setUp(): void
    {
        apcu_clear_cache();
        $this->app = require __DIR__ . '/../../bootstrap/app.php';
    }

    // --- /ping ---

    public function testPingReturns200WithPong(): void
    {
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/ping');

        $response = $this->app->handle($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('pong', (string) $response->getBody());
    }

    // --- /reset ---

    public function testResetReturns200WithOk(): void
    {
        $request = (new ServerRequestFactory())->createServerRequest('POST', '/reset');

        $response = $this->app->handle($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('OK', (string) $response->getBody());
    }

    public function testResetClearsStorageState(): void
    {
        $this->makeDeposit('42', 100.0);

        $reset = (new ServerRequestFactory())->createServerRequest('POST', '/reset');
        $this->app->handle($reset);

        $response = $this->makeDeposit('42', 50.0);
        $body     = json_decode((string) $response->getBody(), true);

        $this->assertSame(50.0, floatval($body['destination']['balance']));
    }

    // --- /event: deposit ---

    public function testDepositCreatesNewAccount(): void
    {
        $response = $this->makeDeposit('100', 10.0);
        $body     = json_decode((string) $response->getBody(), true);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame('100', $body['destination']['id']);
        $this->assertSame(10.0, floatval($body['destination']['balance']));
    }

    public function testDepositAddsBalanceToExistingAccount(): void
    {
        $this->makeDeposit('100', 10.0);
        $response = $this->makeDeposit('100', 10.0);
        $body     = json_decode((string) $response->getBody(), true);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame(20.0, floatval($body['destination']['balance']));
    }

    public function testDepositReturnsJsonContentType(): void
    {
        $response = $this->makeDeposit('200', 50.0);

        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
    }

    // --- /event: withdraw ---

    public function testWithdrawReturns200(): void
    {
        $response = $this->sendEvent(['type' => 'withdraw', 'origin' => '100', 'amount' => 5.0]);

        $this->assertSame(200, $response->getStatusCode());
    }

    // --- /event: transfer ---

    public function testTransferReturns200(): void
    {
        $response = $this->sendEvent(['type' => 'transfer', 'origin' => '100', 'destination' => '300', 'amount' => 15.0]);

        $this->assertSame(200, $response->getStatusCode());
    }

    // --- /event: invalid type ---

    public function testEventWithInvalidTypeReturns400(): void
    {
        $response = $this->sendEvent(['type' => 'unknown']);

        $this->assertSame(400, $response->getStatusCode());
    }

    public function testEventWithoutTypeReturns400(): void
    {
        $response = $this->sendEvent([]);

        $this->assertSame(400, $response->getStatusCode());
    }

    // --- helpers ---

    private function makeDeposit(string $destinationId, float $amount): \Psr\Http\Message\ResponseInterface
    {
        return $this->sendEvent([
            'type'        => 'deposit',
            'destination' => $destinationId,
            'amount'      => $amount,
        ]);
    }

    private function sendEvent(array $body): \Psr\Http\Message\ResponseInterface
    {
        $stream = (new StreamFactory())->createStream(json_encode($body));

        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/event')
            ->withHeader('Content-Type', 'application/json')
            ->withBody($stream);

        return $this->app->handle($request);
    }
}
