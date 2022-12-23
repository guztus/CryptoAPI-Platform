<?php

namespace App\Services\User;

use App\Repositories\User\UserAssetsRepository;

class UserAssetService
{
    public function show(?int $userId, string $symbol): array
    {
        if (!$userId) {
            return [];
        } else
        $asset = (new UserAssetsRepository())->getSingleAsset($userId, $symbol, 'standard');
        $assetShort = (new UserAssetsRepository())->getSingleAsset($userId, $symbol, 'short');

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
}