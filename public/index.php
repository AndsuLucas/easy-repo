<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../scripts/connect_elouquent_mysql_database.php';
require_once __DIR__ . '/../scripts/User.php';

use Andsudev\Easyrepo\Repository\EloquentRepository;

class UserRepository extends EloquentRepository
{
    protected string $modelClass = User::class;
}


$repo = new UserRepository();
$r = $repo->findBy(['age','<=','30']);
echo '<pre>';var_dump($r);exit;
echo 'ok';