<?php

namespace App\Console;

use App\Console\Commands\{
    MakeControllerCommand,
    MakeModelCommand,
    MakeRequestCommand,
    MakeMigrationCommand,
    MakeSeederCommand,
    MakeModuleCommand,
    ServeCommand,
    StopCommand,
    RestartCommand,
    RouteListCommand
};

class CommandRunner
{
    public static function handle(string $command, array $args = [])
    {
        $map = [
            'make:controller' => MakeControllerCommand::class,
            'make:model'      => MakeModelCommand::class,
            'make:request'    => MakeRequestCommand::class,
            'make:migration'  => MakeMigrationCommand::class,
            'make:seeder'     => MakeSeederCommand::class,
            'make:module'     => MakeModuleCommand::class,
            'serve'           => ServeCommand::class,
            'stop'            => StopCommand::class,
            'restart'         => RestartCommand::class,
            'route:list'      => RouteListCommand::class,
        ];

        if (!isset($map[$command])) {
            echo "\n❌ Comando '$command' não reconhecido.\n";
            echo "ℹ️  Use um dos comandos disponíveis:\n";
            foreach (array_keys($map) as $cmd) {
                echo "  - $cmd\n";
            }
            return;
        }

        (new $map[$command])->handle($args);
    }
}