<?php

namespace App\Services;

use App\Models\Collections\AssetCollection;
use App\Repositories\User\UserAssetsRepository;

class PortfolioService
{
    public function getAllAssets(): AssetCollection
    {
        $assetList = (new UserAssetsRepository())->getAssetList($_SESSION['auth_id'])->getAllAssets();

        $updatedAssetList = new AssetCollection();
        foreach ($assetList as $asset) {
            $assetSymbol = ((new CoinsService())->execute($asset->getSymbol()));
            if (!$assetSymbol) {
                continue;
            }
            $asset->setCurrentPrice($assetSymbol->getPrice());
            $updatedAssetList->addAssets($asset);
        }

        return $updatedAssetList;
    }
}