<?php

namespace App\Console\Commands;

/**
 * ServeCommand starts a local PHP development server using the built-in CLI server.
 *
 * Usage:
 * php pluma serve
 * php pluma serve --host=127.0.0.1 --port=8080
 */
class ServeCommand
{
    /**
     * Path to store the running server's PID.
     */
    protected string $pidFile = __DIR__ . '/../../../.pluma-server.pid';

    /**
     * Starts the development server.
     *
     * Accepts optional arguments:
     *   --host=127.0.0.1
     *   --port=8000
     *
     * @param array $args
     */
    public function handle(array $args)
    {
        // Load defaults from .env or use fallback
        $host = get_env('APP_HOST', 'localhost');
        $port = get_env('APP_PORT', 8000);

        // Allow override via CLI flags
        foreach ($args as $arg) {
            if (str_starts_with($arg, '--host=')) {
                $host = explode('=', $arg)[1];
            } elseif (str_starts_with($arg, '--port=')) {
                $port = (int) explode('=', $arg)[1];
            }
        }

        $url = "http://{$host}:{$port}";
        $docRoot = base_path('public');
        $logFile = __DIR__ . '/../../../pluma.log';

        // Check if the port is already in use
        if ($this->isPortInUse($host, $port)) {
            echo "❌ Port {$port} is already in use. Server may already be running.\n";
            return;
        }

        // Inform the user
        echo "🚀 Server running at: \033[1;32m{$url}\033[0m\n";
        echo "📁 Serving from: {$docRoot}\n";
        echo "📝 Logs: pluma.log\n";
        echo "⏹️  Press Ctrl+C to stop\n\n";

        // Run the server as a background process and redirect output
        $command = "php -S {$host}:{$port} -t {$docRoot} >> {$logFile} 2>&1";
        $process = proc_open($command, [], $pipes);

        if (is_resource($process)) {
            $status = proc_get_status($process);
            file_put_contents($this->pidFile, $status['pid']);
            proc_close($process);
        } else {
            echo "❌ Failed to start the server.\n";
        }
    }

    /**
     * Checks if the given port is already in use.
     *
     * @param string $host
     * @param int $port
     * @return bool
     */
    protected function isPortInUse(string $host, int $port): bool
    {
        $connection = @fsockopen($host, $port);
        if (is_resource($connection)) {
            fclose($connection);
            return true;
        }
        return false;
    }
}
