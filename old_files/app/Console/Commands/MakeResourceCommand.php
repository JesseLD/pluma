<?php

namespace App\Console\Commands;

use Core\Generators\ResourceGenerator;

class MakeResourceCommand
{
  public static function run(array $args)
  {
    $name = $args[0] ?? null;

    if (!$name) {
      echo "Resource name is required.\n";
      exit(1);
    }

    $generator = new ResourceGenerator($name);
    $generator->generate();
  }
}
