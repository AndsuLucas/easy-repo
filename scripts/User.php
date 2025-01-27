<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'age'];
}
