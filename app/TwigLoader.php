<?php declare(strict_types=1);

namespace App;

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
        return self::$twig;
    }
}