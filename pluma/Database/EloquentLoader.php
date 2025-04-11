<?php

// ==========================================
// File: pluma/Database/EloquentLoader.php
// Description: Optional loader for Illuminate's Eloquent ORM
// ==========================================

// namespace Pluma\Database;

// use Illuminate\Database\Capsule\Manager as Capsule;

// class EloquentLoader
// {
//     /**
//      * Boot Eloquent ORM if the class is available.
//      *
//      * @param array $config
//      * @return bool
//      */
//     public static function bootIfAvailable(array $config): bool
//     {
//         if (!class_exists(Capsule::class)) {
//             return false;
//         }

//         $capsule = new Capsule;
//         $capsule->addConnection($config);
//         $capsule->setAsGlobal();
//         $capsule->bootEloquent();

//         return true;
//     }
// }