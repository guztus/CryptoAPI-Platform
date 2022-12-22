<?php declare(strict_types=1);

use App\Repositories\Coins\CoinsRepository;
use App\Repositories\Coins\CryptoCoinTable;
use App\Router;
//use App\UserActions;

require_once '../vendor/autoload.php';

session_start();

(Dotenv\Dotenv::createImmutable('../'))->load();

$container = new DI\Container();
//$container->set(CoinsRepository::class, \DI\create(\App\Repositories\Coins\CoinMarketCapCryptoCoinsRepository::class));
$container->set(CoinsRepository::class, \DI\create(CryptoCoinTable::class));

//$container->set(UserActions::class, \DI\create(UserActions::class));

Router::route($container);
