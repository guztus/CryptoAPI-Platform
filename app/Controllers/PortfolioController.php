<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\PortfolioService;
use App\Template;

class PortfolioController
{
    public function index()
    {
        if (empty($_SESSION['auth_id'])) {
            return Redirect::to('/login');
        }
        
        $assetList = ((new PortfolioService())->getAllAssets())->getAllAssets();

        return Template::render('portfolio/portfolio.view.twig', ['assetList' => $assetList ?? []]);
    }
}