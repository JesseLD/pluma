<?php

namespace App\Console\Commands;

/**
 * StopCommand kills the development server started by `serve`,
 * using the PID stored in `.pluma-server.pid`.
 *
 * Usage:
 * php pluma stop
 */
class StopCommand
{
    /**
     * Handles the stop command.
     * Looks for the PID file and kills the process.
     *
     * @param array $args (unused)
     */
    public function handle(array $args)
    {
        $pidFile = __DIR__ . '/../../../.pluma-server.pid';

        if (!file_exists($pidFile)) {
            echo "⚠️  No active server found (PID file missing).\n";
            return;
        }

        $pid = trim(file_get_contents($pidFile));

        if (!$this->processIsRunning($pid)) {
            echo "⚠️  Process $pid is not running. Cleaning up PID file.\n";
            unlink($pidFile);
            return;
        }

        // Kill the process depending on the OS
        if (stripos(PHP_OS, 'WIN') !== false) {
            exec("taskkill /F /PID $pid");
        } else {
            exec("kill -9 $pid");
        }

        unlink($pidFile);
        echo "🛑 Server stopped (PID $pid).\n";
    }

    /**
     * Checks if a process with the given PID is running.
     *
     * @param int|string $pid
     * @return bool
     */
    protected function processIsRunning($pid): bool
    {
        if (stripos(PHP_OS, 'WIN') !== false) {
            exec("tasklist | find \"$pid\"", $output);
            return count($output) > 0;
        } else {
            return file_exists("/proc/$pid");
        }
    }
}
