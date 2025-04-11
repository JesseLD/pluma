<?php

// ==========================================
// File: pluma/Console/Command.php
// Description: Abstract base for console commands
// ==========================================

namespace Pluma\Console;

abstract class Command
{
  /**
   * The name and signature of the command.
   *
   * @var string
   */
  protected string $signature;

  /**
   * A description of what the command does.
   *
   * @var string
   */
  protected string $description;

  /**
   * Execute the command logic.
   * This method must be implemented by each concrete command class.
   */
  abstract public function handle(): void;

  /**
   * Output an informational message.
   *
   * @param string $message
   */
  protected function info(string $message): void
  {
    echo "\e[32m{$message}\e[0m" . PHP_EOL;
  }

  /**
   * Output an error message.
   *
   * @param string $message
   */
  protected function error(string $message): void
  {
    echo "\e[31m{$message}\e[0m" . PHP_EOL;
  }

  /**
   * Get the command signature.
   *
   * @return string
   */
  public function getSignature(): string
  {
    return $this->signature;
  }
}
