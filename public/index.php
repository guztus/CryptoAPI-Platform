<?php declare(strict_types=1);

use App\Router;
use App\Session;

require_once '../vendor/autoload.php';

Session::start();

(Dotenv\Dotenv::createImmutable('../'))->load();

//    $_SESSION['errors'] ?? []

//var_dump(Session::has('test') ? [] : Session::);

Router::route();
