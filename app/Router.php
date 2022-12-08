<?php declare(strict_types=1);

namespace App;

use App\Controllers\LoginController;
use App\Controllers\RegistrationController;
use FastRoute;
use App\Controllers\MainController;

class Router
{
    public static function route()
    {
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $router) {
            $router->addRoute('GET', '/', [MainController::class, 'index']);
            $router->addRoute('GET', '/register', [RegistrationController::class, 'index']);
            $router->addRoute('GET', '/login', [LoginController::class, 'index']);
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
                var_dump('404 Not Found');

                // ... 404 Not Found
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                var_dump('405 Meth Not Allowed');

                // ... 405 Method Not Allowed
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
//                $vars = $routeInfo[2];

                [$controller, $method] = $handler;

                $twig = (new TwigLoader())->getTwig();

//                var_dump((new $controller)->{$method}());die;
                $response = (new $controller)->{$method}();
                if ($response instanceof Template) {
                    echo $twig->render($response->getPath(), $response->getParameters());
                }

                if ($response instanceof Redirect) {
                    header($response->getPath());
                }
        }
    }
}