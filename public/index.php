<?php declare(strict_types=1);

use App\Router;

require_once '../vendor/autoload.php';

session_start();

(Dotenv\Dotenv::createImmutable('../'))->load();

Router::route();
