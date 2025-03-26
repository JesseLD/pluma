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

/**
 * CommandRunner is the CLI dispatcher for Pluma console commands.
 * It maps command names (e.g., make:model) to their corresponding classes
 * and invokes the appropriate handler with given arguments.
 */
class CommandRunner
{
    /**
     * Handles the execution of a CLI command.
     *
     * @param string $command The command string (e.g., 'make:model')
     * @param array $args Optional arguments passed to the command
     *
     * Example:
     * CommandRunner::handle('make:model', ['User']);
     */
    public static function handle(string $command, array $args = [])
    {
        // Command to class map
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

        // Command not found
        if (!isset($map[$command])) {
            echo "\n❌ Command '$command' not recognized.\n";
            echo "ℹ️  Available commands:\n";
            foreach (array_keys($map) as $cmd) {
                echo "  - $cmd\n";
            }
            return;
        }

        // Instantiate the command class and execute
        (new $map[$command])->handle($args);
    }
}
