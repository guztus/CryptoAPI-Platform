<?php

namespace App\Services\User;

use App\Repositories\User\UserAssetsRepository;

class UserAssetsService
{
    public function getAssetAmount(int $userId, string $symbol): float
    {
        return (new UserAssetsRepository())->getAssetAmount($userId, $symbol);
    }
}