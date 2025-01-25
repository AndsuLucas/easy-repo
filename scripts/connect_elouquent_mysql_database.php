<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager;

$capsule = new Manager();

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'mysql',
    'database'  => 'playground',
    'username'  => 'playground',
    'password'  => 'password',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();

$capsule->bootEloquent();
