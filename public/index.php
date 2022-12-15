<?php declare(strict_types=1);

use App\Repositories\Coins\CoinsRepository;
use App\Repositories\Coins\CryptoAPIPlatformCryptoCoinTable;
use App\Router;

require_once '../vendor/autoload.php';

session_start();

(Dotenv\Dotenv::createImmutable('../'))->load();

$container = new DI\Container();
//$container->set(CoinsRepository::class, \DI\create(\App\Repositories\Coins\CoinMarketCapCryptoCoinsRepository::class));
$container->set(CoinsRepository::class, \DI\create(CryptoAPIPlatformCryptoCoinTable::class));

Router::route($container);
