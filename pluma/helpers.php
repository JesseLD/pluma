<?php

// ==========================================
// File: pluma/helpers.php
// Description: Global helper functions used throughout the framework
// ==========================================

if (!function_exists('base_path')) {
  /**
   * Get the base path of the application.
   *
   * @param string $path
   * @return string
   */
  function base_path(string $path = ''): string
  {
      return __DIR__ . '/..' . ($path ? DIRECTORY_SEPARATOR . $path : '');
  }
}
