<?php

namespace Core\Generators;

/**
 * Generates a resource (Controller, Model, Request) for a given name.
 * Inspired by Laravel's artisan make:resource.
 */
class ResourceGenerator
{
  protected string $name;
  protected string $namePlural;
  protected string $table;

  public function __construct(string $name)
  {
    $this->name = ucfirst($name);
    $this->namePlural = $this->pluralize($this->name);
    $this->table = strtolower($this->namePlural);
  }

  /**
   * Entry point: generates the Controller, Model and Request.
   */
  public function generate(): void
  {
    $this->generateFromStub('Controller', "app/Controllers/{$this->name}Controller.php");
    $this->generateFromStub('Model', "app/Models/{$this->name}.php");
    $this->generateFromStub('Request', "app/Requests/{$this->name}Request.php");

    echo "\n[Resource] {$this->name} created successfully.\n";
    echo "Don't forget to add your routes in routes/web.php\n";
  }

  /**
   * Reads a stub file, replaces placeholders and writes the result to destination.
   */
  protected function generateFromStub(string $type, string $destination): void
  {
    $stubPath = __DIR__ . "/../../stubs/resource/{$type}.stub";
    if (!file_exists($stubPath)) {
      echo "Stub for {$type} not found.\n";
      return;
    }

    $stub = file_get_contents($stubPath);
    $output = str_replace([
      '{{name}}',
      '{{namePlural}}',
      '{{table}}'
    ], [
      $this->name,
      $this->namePlural,
      $this->table
    ], $stub);

    $dir = dirname($destination);
    if (!is_dir($dir)) {
      mkdir($dir, 0755, true);
    }

    file_put_contents($destination, $output);
  }

  /**
   * Naive pluralization: adds 's' (for demo purposes).
   * In production, consider using a library or more robust logic.
   */
  protected function pluralize(string $word): string
  {
    return rtrim($word, 's') . 's';
  }
}
