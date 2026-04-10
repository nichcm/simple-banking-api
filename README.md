# simple-banking-api

A lightweight in-memory banking API supporting account creation, balance inquiry, deposits, withdrawals, and transfers through an event-driven interface.

## Requisitos

- Docker
- Docker Compose

## Como rodar

```bash
docker compose up --build
```

A API estará disponível em `http://localhost:8080`.

## Rotas disponíveis

```bash
curl http://localhost:8080/hello-world
```

## Parar os containers

```bash
docker compose down
```
