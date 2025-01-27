<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

require_once './connect_elouquent_mysql_database.php';

Manager::schema()->create('users', function ($table) {
    $table->increments('id');
    $table->string('name');
    $table->string('email')->unique();
    $table->integer('age');
    $table->timestamps();
});

require 'User.php';

User::insert([
    ['name' => 'John Doe', 'email' => 'john.doe@example.com', 'age' => random_int(10, 65)],
    ['name' => 'Jane Smith', 'email' => 'jane.smith@example.com', 'age' => random_int(10, 65)],
    ['name' => 'Alice Johnson', 'email' => 'alice.johnson@example.com', 'age' => random_int(10, 16)],
    ['name' => 'Bob Brown', 'email' => 'bob.brown@example.com', 'age' => random_int(10, 65)],
    ['name' => 'Charlie Davis', 'email' => 'charlie.davis@example.com', 'age' => random_int(10, 65)],
    ['name' => 'Emily Wilson', 'email' => 'emily.wilson@example.com', 'age' => random_int(10, 65)],
    ['name' => 'Frank Moore', 'email' => 'frank.moore@example.com', 'age' => random_int(10, 30)],
    ['name' => 'Grace Taylor', 'email' => 'grace.taylor@example.com', 'age' => random_int(10, 65)],
    ['name' => 'Hank Lee', 'email' => 'hank.lee@example.com', 'age' => random_int(10, 65)],
    ['name' => 'Ivy Walker', 'email' => 'ivy.walker@example.com', 'age' => random_int(10, 65)],
]);

echo "Setup complete.";