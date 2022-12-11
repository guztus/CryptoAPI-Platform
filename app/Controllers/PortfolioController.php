<?php

namespace App\Controllers;

use App\Services\PortfolioService;
use App\Template;

class PortfolioController
{
    public function index(): Template
    {
        $assetList = ((new PortfolioService())->getAllAssets())->getAllAssets();

        return Template::render('portfolio/portfolio.view.twig', ['assetList' => $assetList ?? []]);
    }
}