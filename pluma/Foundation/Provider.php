<?php
// ==========================================
// File: pluma/Foundation/Provider.php
// Description: Base class for service providers
// ==========================================

namespace Pluma\Foundation;

abstract class Provider
{
    /**
     * Register services within the container.
     *
     * @param Application $app
     */
    abstract public function register(Application $app): void;
}
