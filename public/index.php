<?php declare(strict_types=1);

use App\Router;
use App\Session;

require_once '../vendor/autoload.php';

Session::start();

(Dotenv\Dotenv::createImmutable('../'))->load();

Router::route();
