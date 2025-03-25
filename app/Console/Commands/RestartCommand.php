<?php

namespace App\Console\Commands;

use App\Console\Commands\StopCommand;
use App\Console\Commands\ServeCommand;

class RestartCommand
{
    public function handle(array $args)
    {
        echo "🔁 Reiniciando servidor...\n";

        // Para o servidor
        (new StopCommand())->handle([]);

        sleep(1); // pequeno delay de segurança

        // Reinicia com mesmos args (host/port)
        (new ServeCommand())->handle($args);
    }
}
