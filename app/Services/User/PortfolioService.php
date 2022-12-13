<?php

namespace App\Services\User;

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

    public function execute(): AssetCollection
    {
        return (new UserAssetsRepository($this->coinsRepository))
            ->getAssetList($_SESSION['auth_id']);
    }
}