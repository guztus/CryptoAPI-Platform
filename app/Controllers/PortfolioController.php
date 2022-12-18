<?php declare(strict_types=1);

namespace App\Controllers;

use App\Redirect;
use App\Repositories\Coins\CoinsRepository;
use App\Repositories\User\UserAssetsRepository;
use App\Services\User\Assets\UserAssetsListService;
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

        $assetList = (new UserAssetsRepository($this->coinsRepository))
            ->getAssetList($_SESSION['auth_id']);

        return Template::render('portfolio/portfolio.view.twig', ['assetList' => $assetList->getAllAssets() ?? []]);
    }
}