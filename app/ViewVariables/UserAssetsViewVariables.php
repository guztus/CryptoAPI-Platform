<?php

namespace App\ViewVariables;

use App\Repositories\User\UserAssetsRepository;

class UserAssetsViewVariables implements ViewVariablesInterface
{
    public function getName(): string
    {
        return 'userAsset';
    }

    public function getValue(): array
    {
        if (!empty($_SESSION['auth_id']) && !empty($_GET['search'])) {
            $asset = (new UserAssetsRepository())->getSingleAsset($_SESSION['auth_id'], $_GET['search']);

//            var_dump($asset);die;

            if ($asset) {
                return [
                    'amount' => $asset->getAmount() ?? null,
                    'average_cost' => $asset->getAverageCost() ?? null,
//                    'total_cost' => $asset->getTotalCost() ?? null,
                ];
            }
        }
        return [];
    }
}