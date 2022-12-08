<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\CoinsService;
use App\Template;

class MainController
{
    public function index(): Template
    {
        $coinList = ((new CoinsService())->execute())->getCoins();
        return new Template('/main/main.view.twig', ['coins' => $coinList]);
    }
}