<?php declare(strict_types=1);

namespace App;

use App\ViewVariables\AlertsViewVariables;
use App\ViewVariables\AuthViewVariables;
use App\ViewVariables\ErrorsViewVariables;
use App\ViewVariables\UserAssetsViewVariables;
use App\ViewVariables\ViewVariablesInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigLoader
{
    private static ?Environment $twig = null;

    public function getTwig(): Environment
    {
        if (self::$twig == null) {
            $loader = new FilesystemLoader('views');
            self::$twig = new Environment($loader);
        }
        $this->addGlobal();
        return self::$twig;
    }

    private function addGlobal()
    {
        $authVariables = [
            AuthViewVariables::class,
            AlertsViewVariables::class,
            ErrorsViewVariables::class,
            UserAssetsViewVariables::class
        ];

        foreach ($authVariables as $variable) {
            /**  @var ViewVariablesInterface $variable */
            self::$twig->addGlobal((new $variable)->getName(), (new $variable)->getValue());
        }
    }
}