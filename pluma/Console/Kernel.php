<?php

// ==========================================
// File: pluma/Console/Kernel.php
// Description: Console kernel that holds and executes registered commands
// ==========================================

namespace Pluma\Console;

class Kernel
{
    /**
     * List of registered command instances.
     *
     * @var Command[]
     */
    protected array $commands = [];

    /**
     * Register a command in the kernel.
     *
     * @param Command $command
     */
    public function register(Command $command): void
    {
        $this->commands[] = $command;
    }

    /**
     * Run a command by matching its signature.
     *
     * @param string $input
     */
    public function handle(string $input): void
    {
        foreach ($this->commands as $command) {
            if ($input === $command->getSignature()) {
                $command->handle();
                return;
            }
        }

        echo "\e[33mCommand '{$input}' not found.\e[0m" . PHP_EOL;
    }
}
