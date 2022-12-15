<?php

namespace App\Services\User\Assets;

use App\Models\Collections\AssetCollection;
use App\Repositories\Coins\CoinsRepository;
use App\Repositories\User\UserAssetsRepository;

class UserAssetsListService
{
    private CoinsRepository $coinsRepository;

    public function __construct(CoinsRepository $coinsRepository)
    {
        $this->coinsRepository = $coinsRepository;
    }

    public function execute(int $id): ?AssetCollection
    {
        return (new UserAssetsRepository($this->coinsRepository))
            ->getAssetList($id);
    }
}