<?php

use App\Router;

require_once '../vendor/autoload.php';

(Dotenv\Dotenv::createImmutable('../'))->load();

Router::route();
