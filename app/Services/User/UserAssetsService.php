<?php declare(strict_types=1);

namespace App\Services\User;

use App\Repositories\User\UserAssetsRepository;

class UserAssetsService
{
    public function getAssetAmount(
        int    $userId,
        string $symbol
    ): float
    {
        return (new UserAssetsRepository())
            ->getAssetAmount($userId, $symbol);
    }

    public function modifyAssets(
        int    $userId,
        string $symbol,
        float  $amount,
        float  $averageCost,
        string $transactionType
    ): void
    {
        (new UserAssetsRepository())
            ->modifyAssets($userId, $symbol, $amount, $averageCost, $transactionType);
    }
}