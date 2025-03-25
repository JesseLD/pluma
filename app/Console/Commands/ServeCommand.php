<?php

namespace App\Console\Commands;

class ServeCommand
{
    protected string $pidFile = __DIR__ . '/../../../.pluma-server.pid';

    public function handle(array $args)
    {   

        $host = get_env('APP_HOST', 'localhost');
        $port = get_env('APP_PORT', 8000);

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

        if ($this->isPortInUse($host, $port)) {
            echo "❌ Porta {$port} já está em uso. Talvez o servidor já esteja rodando.\n";
            return;
        }

        echo "🚀 Servidor iniciado em: \033[1;32m{$url}\033[0m\n";
        echo "📁 Pasta pública: {$docRoot}\n";
        echo "📝 Logs em: pluma.log\n";
        echo "⏹️  Pressione Ctrl+C para parar\n\n";

        $command = "php -S {$host}:{$port} -t {$docRoot} >> {$logFile} 2>&1";
        $process = proc_open($command, [], $pipes);

        if (is_resource($process)) {
            $status = proc_get_status($process);
            file_put_contents($this->pidFile, $status['pid']);
            proc_close($process);
        } else {
            echo "❌ Erro ao iniciar o servidor.\n";
        }
    }

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
