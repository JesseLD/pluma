<?php
// ==========================================
// File: pluma/Console/Commands/MakeModelCommand.php
// Description: Generates a new model class file based on a stub
// ==========================================

namespace Pluma\Console\Commands;

use Pluma\Console\Command;

class MakeModelCommand extends Command
{
    protected string $signature = 'make:model';
    protected string $description = 'Generate a new model class';

    public function getSignature(): string
    {
        return $this->signature;
    }

    public function handle(): void
    {
        global $argv;
        $name = $argv[2] ?? null;

        if (!$name) {
            $this->error("Please provide a model name.");
            return;
        }

        $stub = file_get_contents(__DIR__ . '/../Stubs/model.stub');
        $content = str_replace(['{{ class }}'], [$name], $stub);
        $content = str_replace(['{{ table }}'], [strtolower($name)], $content);

        $path = base_path("app/Models/{$name}.php");
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, $content);
        $this->info("Model '{$name}' created successfully at app/Models/{$name}.php");
    }
}