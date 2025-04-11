<?php

namespace App\Console\Commands;

use App\Console\Commands\StopCommand;
use App\Console\Commands\ServeCommand;

/**
 * RestartCommand stops and then restarts the local development server.
 * It reuses the same arguments passed to `serve`, such as host and port.
 *
 * Usage:
 * php pluma restart
 * php pluma restart 127.0.0.1 8000
 */
class RestartCommand
{
    /**
     * Handles the command.
     * First stops the server, then restarts it after a short delay.
     *
     * @param array $args Optional arguments (host, port)
     */
    public function handle(array $args)
    {
        echo "🔁 Restarting development server...\n";

        // Stop the running server
        (new StopCommand())->handle([]);

        // Delay to ensure clean restart
        sleep(1);

        // Start server again with the same arguments
        (new ServeCommand())->handle($args);
    }
}
