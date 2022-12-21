<?php declare(strict_types=1);

use App\Repositories\Coins\CoinsRepository;
use App\Repositories\Coins\CryptoCoinTable;
use App\Repositories\User\UserShortPositionsRepository;
use App\Router;
use App\UserActions;

require_once '../vendor/autoload.php';

session_start();

(Dotenv\Dotenv::createImmutable('../'))->load();

$container = new DI\Container();
//$container->set(CoinsRepository::class, \DI\create(\App\Repositories\Coins\CoinMarketCapCryptoCoinsRepository::class));
$container->set(CoinsRepository::class, \DI\create(CryptoCoinTable::class));

//$container->set(UserActions::class, \DI\create(UserActions::class));
(new UserShortPositionsRepository())->updatePosition(1, 'short', 'BTC', 0.1, 10000);

//(new UserShortPositionsRepository())->modifyPositions(1, 'buy', 'BTC', 1, 1000);die;
//var_dump((new UserShortPositionsRepository())->getShortPositions(1));die;
//die;


Router::route($container);
