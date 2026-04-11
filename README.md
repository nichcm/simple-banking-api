# simple-banking-api

A lightweight in-memory banking API supporting account creation, balance inquiry, deposits, withdrawals, and transfers through an event-driven interface.

## Requirements

- Docker
- Docker Compose

## Running

```bash
docker compose up --build
```

The API will be available at `http://localhost:8080`.

## Available routes

| Method | Route | Description |
|---|---|---|
| GET | `/ping` | Health check |
| POST | `/reset` | Reset all accounts |
| GET | `/balance?account_id={id}` | Get account balance |
| POST | `/event` | Trigger an event (deposit, withdraw, transfer) |

### Event examples

**Deposit**
```bash
curl -X POST http://localhost:8080/event \
  -H "Content-Type: application/json" \
  -d '{"type": "deposit", "destination": "100", "amount": 10}'
```

**Withdraw**
```bash
curl -X POST http://localhost:8080/event \
  -H "Content-Type: application/json" \
  -d '{"type": "withdraw", "origin": "100", "amount": 5}'
```

**Transfer**
```bash
curl -X POST http://localhost:8080/event \
  -H "Content-Type: application/json" \
  -d '{"type": "transfer", "origin": "100", "destination": "300", "amount": 15}'
```

## Running tests

With the containers running (`docker compose up -d`):

```bash
# Run tests
docker compose exec php vendor/bin/phpunit

# Run tests with HTML coverage report (output: coverage/html/)
docker compose exec -e XDEBUG_MODE=coverage php vendor/bin/phpunit --coverage-html coverage/html
```

To view the coverage report, open `coverage/html/index.html` in your browser.

## Stopping containers

```bash
docker compose down
```
