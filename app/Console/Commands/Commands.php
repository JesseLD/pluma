<?php

namespace App\Console\Commands;

class MakeControllerCommand
{
    public function handle(array $args)
    {
        if (empty($args[0])) {
            echo "⚠️  Informe o nome do controller\n";
            return;
        }

        $name = $args[0];
        $className = str_ends_with($name, 'Controller') ? $name : $name . 'Controller';
        $filePath = __DIR__ . '/../../../Controllers/' . $className . '.php';

        if (file_exists($filePath)) {
            echo "❌ O controller '$className' já existe.\n";
            return;
        }

        $stub = file_get_contents(__DIR__ . '/../../../stubs/controller.stub');
        $stub = str_replace('{{class}}', $className, $stub);

        file_put_contents($filePath, $stub);
        echo "✅ Controller criado: $className\n";
    }
}

class MakeModelCommand
{
    public function handle(array $args)
    {
        if (empty($args[0])) {
            echo "⚠️  Informe o nome do model\n";
            return;
        }

        $name = ucfirst($args[0]);
        $filePath = __DIR__ . '/../../../Models/' . $name . '.php';

        if (file_exists($filePath)) {
            echo "❌ O model '$name' já existe.\n";
            return;
        }

        $stub = file_get_contents(__DIR__ . '/../../../stubs/model.stub');
        $stub = str_replace(['{{class}}', '{{table}}'], [$name, strtolower($name) . 's'], $stub);

        file_put_contents($filePath, $stub);
        echo "✅ Model criado: $name\n";
    }
}

class MakeRequestCommand
{
    public function handle(array $args)
    {
        if (empty($args[0])) {
            echo "⚠️  Informe o nome da request\n";
            return;
        }

        $name = ucfirst($args[0]);
        $className = str_ends_with($name, 'Request') ? $name : $name . 'Request';
        $filePath = __DIR__ . '/../../../Requests/' . $className . '.php';

        if (file_exists($filePath)) {
            echo "❌ A request '$className' já existe.\n";
            return;
        }

        $stub = file_get_contents(__DIR__ . '/../../../stubs/request.stub');
        $stub = str_replace('{{class}}', $className, $stub);

        file_put_contents($filePath, $stub);
        echo "✅ Request criada: $className\n";
    }
}

class MakeMigrationCommand
{
    public function handle(array $args)
    {
        if (empty($args[0])) {
            echo "⚠️  Informe o nome da migration\n";
            return;
        }

        $name = $args[0];
        $timestamp = date('Y_m_d_His');
        $className = 'Migration' . str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
        $filename = "{$timestamp}_{$name}.php";
        $filePath = __DIR__ . "/../../../database/migrations/$filename";

        if (file_exists($filePath)) {
            echo "❌ A migration '$filename' já existe.\n";
            return;
        }

        $stub = file_get_contents(__DIR__ . '/../../../stubs/migration.stub');
        $stub = str_replace(['{{class}}', '{{table}}'], [$className, $name], $stub);

        file_put_contents($filePath, $stub);
        echo "✅ Migration criada: $filename\n";
    }
}

class MakeSeederCommand
{
    public function handle(array $args)
    {
        if (empty($args[0])) {
            echo "⚠️  Informe o nome do seeder\n";
            return;
        }

        $name = ucfirst($args[0]);
        $className = str_ends_with($name, 'Seeder') ? $name : $name . 'Seeder';
        $filePath = __DIR__ . '/../../../database/seeds/' . $className . '.php';

        if (file_exists($filePath)) {
            echo "❌ O seeder '$className' já existe.\n";
            return;
        }

        $stub = file_get_contents(__DIR__ . '/../../../stubs/seeder.stub');
        $stub = str_replace('{{class}}', $className, $stub);

        file_put_contents($filePath, $stub);
        echo "✅ Seeder criado: $className\n";
    }
}

class MakeModuleCommand
{
    public function handle(array $args)
    {
        if (empty($args[0])) {
            echo "⚠️  Informe o nome do módulo\n";
            return;
        }

        $name = ucfirst($args[0]);
        $table = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name)) . 's';
        $timestamp = date('Y_m_d_His');

        $map = [
            'Controllers' => ['controller.stub', "{$name}Controller.php", "{$name}Controller"],
            'Models' => ['model.stub', "{$name}.php", $name],
            'Requests' => ['request.stub', "{$name}Request.php", "{$name}Request"],
            'database/migrations' => ['migration.stub', "{$timestamp}_create_{$table}_table.php", "Create" . ucfirst($table) . "Table"],
            'database/seeds' => ['seeder.stub', "{$name}Seeder.php", "{$name}Seeder"]
        ];

        foreach ($map as $folder => [$stubFile, $filename, $class]) {
            $dir = __DIR__ . '/../../../' . $folder;
            if (!is_dir($dir)) mkdir($dir, 0777, true);

            $stub = file_get_contents(__DIR__ . "/../../../stubs/module/$stubFile");
            $stub = str_replace(['{{class}}', '{{table}}', '{{name}}'], [$class, $table, $name], $stub);

            $path = "$dir/$filename";
            if (file_exists($path)) {
                echo "❌ Já existe: $filename\n";
                continue;
            }

            file_put_contents($path, $stub);
            echo "✅ Criado: $path\n";
        }
    }
}
