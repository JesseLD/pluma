<?php

// ==========================================
// File: pluma/Foundation/Application.php
// Description: Core application container that handles service binding and resolution
// ==========================================

namespace Pluma\Foundation;

class Application
{
    /**
     * The array of service bindings.
     *
     * @var array
     */
    protected array $bindings = [];

    /**
     * Bind a service into the container.
     *
     * @param string $abstract
     * @param callable $concrete
     */
    public function bind(string $abstract, callable $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Resolve a service from the container.
     *
     * @param string $abstract
     * @return mixed
     * @throws \Exception
     */
    public function resolve(string $abstract): mixed
    {
        if (!isset($this->bindings[$abstract])) {
            throw new \Exception("No binding found for {$abstract}");
        }

        return call_user_func($this->bindings[$abstract]);
    }
}