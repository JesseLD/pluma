<?php

namespace App\Console\Commands;

class StopCommand
{
    public function handle(array $args)
    {
        $pidFile = __DIR__ . '/../../../.pluma-server.pid';

        if (!file_exists($pidFile)) {
            echo "⚠️  Nenhum servidor ativo encontrado (PID ausente).\n";
            return;
        }

        $pid = trim(file_get_contents($pidFile));

        if (!$this->processIsRunning($pid)) {
            echo "⚠️  Processo $pid não está mais rodando. Limpando PID.\n";
            unlink($pidFile);
            return;
        }

        if (stripos(PHP_OS, 'WIN') !== false) {
            exec("taskkill /F /PID $pid");
        } else {
            exec("kill -9 $pid");
        }

        unlink($pidFile);
        echo "🛑 Servidor finalizado (PID $pid).\n";
    }

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
