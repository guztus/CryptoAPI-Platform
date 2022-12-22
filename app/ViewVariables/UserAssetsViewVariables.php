<?php

namespace App\ViewVariables;

use App\Repositories\User\UserAssetsRepository;

class UserAssetsViewVariables implements ViewVariablesInterface
{
    public function getName(): string
    {
        return 'asset';
    }

    public function getValue(): array
    {
        if (!empty($_SESSION['auth_id']) && !empty($_GET['search'])) {
            $asset = (new UserAssetsRepository())->getSingleAsset($_SESSION['auth_id'], $_GET['search']);
            $assetShort = (new UserAssetsRepository())->getSingleAsset($_SESSION['auth_id'], $_GET['search'], 'short');

            if ($asset) {
                $standardAssetInformation[] = [
                    'amount' => $asset->getAmount() ?? null,
                    'average_cost' => $asset->getAverageCost() ?? null,
                ];
            }
            if ($assetShort) {
                $shortAssetInformation[] = [
                    'short_amount' => $assetShort->getAmount() ?? null,
                    'short_average_cost' => $assetShort->getAverageCost() ?? null,
                ];
            }
            return array_merge($standardAssetInformation[0] ?? [], $shortAssetInformation[0] ?? []);
        }
        return [];
    }
}