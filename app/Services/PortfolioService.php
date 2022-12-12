<?php

namespace App\Services;

use App\Models\Collections\AssetCollection;
use App\Repositories\Coins\CoinsRepository;
use App\Repositories\User\UserAssetsRepository;

class PortfolioService
{
    private CoinsRepository $coinsRepository;

    public function __construct(CoinsRepository $coinsRepository)
    {
        $this->coinsRepository = $coinsRepository;
    }

    public function getAllAssets(): AssetCollection
    {
        $assetList = (new UserAssetsRepository())->getAssetList($_SESSION['auth_id'])->getAllAssets();

        $updatedAssetList = new AssetCollection();
        // adds setCurrent price to each asset for profit calculations in Portfolio view
        foreach ($assetList as $asset) {
            $assetSymbol = ((new CoinsService($this->coinsRepository))->execute($asset->getSymbol()));
            if (!$assetSymbol) {
                continue;
            }
            $asset->setCurrentPrice($assetSymbol->getPrice());
            $updatedAssetList->addAssets($asset);
        }

        return $updatedAssetList;
    }
}