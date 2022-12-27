<?php declare(strict_types=1);

use App\Repositories\Coins\CoinsRepository;
use App\Router;

require_once '../vendor/autoload.php';
$coinInformationSource = include('../dataSourceConfig.php');

session_start();

(Dotenv\Dotenv::createImmutable('../'))->load();

$container = new DI\Container();

$container->set(CoinsRepository::class, \DI\create($coinInformationSource['source']));

Router::route($container);
