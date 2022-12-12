<?php

namespace App\Controllers;

use App\Redirect;
use App\Repositories\Coins\CoinsRepository;
use App\Services\PortfolioService;
use App\Template;

class PortfolioController
{
    private CoinsRepository $coinsRepository;

    public function __construct(CoinsRepository $coinsRepository)
    {
        $this->coinsRepository = $coinsRepository;
    }

    public function index()
    {
        if (empty($_SESSION['auth_id'])) {
            return Redirect::to('/login');
        }
        
        $assetList = ((new PortfolioService($this->coinsRepository))->getAllAssets())->getAllAssets();

        return Template::render('portfolio/portfolio.view.twig', ['assetList' => $assetList ?? []]);
    }
}