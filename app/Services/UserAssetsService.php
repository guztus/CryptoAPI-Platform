<?php

namespace App\Services;

use App\Repositories\User\UserAssetsRepository;

class UserAssetsService
{
    public function getAssetAmount(int $userId, string $symbol): float
    {
        return (new UserAssetsRepository())->getAssetAmount($userId, $symbol);
    }
}