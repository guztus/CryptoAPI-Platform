<?php declare(strict_types=1);

namespace App;

use App\Controllers\CoinsController;
use App\Controllers\LoginController;
use App\Controllers\LogoutController;
use App\Controllers\PortfolioController;
use App\Controllers\ProfileController;
use App\Controllers\RegistrationController;
use App\Controllers\ViewProfileController;
use DI\Container;
use FastRoute;

class Router
{
    public static function route(Container $container)
    {
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $router) {
            $router->addRoute('GET', '/', [CoinsController::class, 'index']);

            $router->addRoute('POST', '/', [CoinsController::class, 'doTransaction']);
//            $router->addRoute('POST', '/buySell', [CoinsController::class, 'buySell']);
//            $router->addRoute('POST', '/short', [CoinsController::class, 'shortPosition']);

            $router->addRoute('GET', '/register', [RegistrationController::class, 'showForm']);
            $router->addRoute('POST', '/register', [RegistrationController::class, 'register']);

            $router->addRoute('GET', '/login', [LoginController::class, 'showForm']);
            $router->addRoute('POST', '/login', [LoginController::class, 'login']);

            $router->addRoute('GET', '/logout', [LogoutController::class, 'logout']);

            $router->addRoute('GET', '/profile', [ProfileController::class, 'show']);
            $router->addRoute('POST', '/profile', [ProfileController::class, 'updateBalance']);

            $router->addRoute('GET', '/portfolio', [PortfolioController::class, 'show']);

            $router->addRoute('GET', '/profile/', [ViewProfileController::class, 'searchUser']);
            $router->addRoute('POST', '/profile/', [ViewProfileController::class, 'getUser']);

            $router->addRoute('GET', '/profile/{id:\d+}', [ViewProfileController::class, 'show']);
            $router->addRoute('POST', '/profile/{id:\d+}', [ViewProfileController::class, 'sendCoins']);
        });

// Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:

                // ... 404 Not Found
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];

                // ... 405 Method Not Allowed
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                [$controller, $method] = $handler;

                $twig = (new TwigLoader())->getTwig();

                $response = $container->get($controller)->{$method}($vars);

                if ($response instanceof Template) {
                    echo $twig->render($response->getPath(), $response->getParameters());

                    unset($_SESSION['errors']);
                    unset($_SESSION['alerts']);
                }

                if ($response instanceof Redirect) {
                    header("Location: " . $response->getPath());
                }
        }
    }
}