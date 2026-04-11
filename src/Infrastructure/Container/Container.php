<?php

namespace App\Infrastructure\Container;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $bindings = [];

    public function set(string $id, callable $factory): void
    {
        $this->bindings[$id] = $factory;
    }

    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new \RuntimeException("Binding not found: {$id}");
        }

        return ($this->bindings[$id])();
    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]);
    }
}
