<?php

require_once __DIR__ . '/vendor/autoload.php';

use Core\Facades\Router;
use Core\Database;
use Core\DataSources\MySQLDataSource;

$ds = new MySQLDataSource(
    'localhost',
    'fil_rouge_rattrapage',
    'root',
    ''
);
Database::init($ds);

$router = new Router();
$router->dispatch();
