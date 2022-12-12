<?php

namespace App\Repositories\User;

use App\Database;
use App\Models\Asset;
use App\Models\Collections\AssetCollection;

class UserAssetsRepository
{
    private Database $database;

    public function __construct()
    {
        $this->database = (new Database());
    }

    public function modifyAssets(int $userId, string $symbol, float $amount, float $dollarCostAverage, string $operation): void
    {
        $userHasThisAsset = ($this->database)
            ->getConnection()
            ->fetchAllAssociative('SELECT * FROM user_assets WHERE user_id = ? and symbol = ?', [$userId, $symbol]);

        if (empty($userHasThisAsset)) {
            $query = ($this->database)
                ->getConnection()
                ->createQueryBuilder()
                ->insert('user_assets')
                ->values([
                    'user_id' => '?',
                    'symbol' => '?',
                    'amount' => '?',
                    'average_cost' => '?'
                ])
                ->setParameter(0, $userId)
                ->setParameter(1, $symbol)
                ->setParameter(2, $amount)
                ->setParameter(3, $dollarCostAverage);
        } else {
            if ($operation == 'sell') {
                $operator = '-';
            } else {
                $operator = '+';
            }

            $oldDollarCostAverage = $this->getOldDollarCostAverage($userId, $symbol);
            $currentDollarCostAverage = ($oldDollarCostAverage + $dollarCostAverage) / 2;

            $query = ($this->database)
                ->getConnection()
                ->createQueryBuilder()
                ->update('user_assets')
                ->set('amount', "amount $operator $amount")
                ->set('average_cost', $currentDollarCostAverage)
                ->where("user_id = $userId", "symbol = '$symbol'");
        }
        $query->executeStatement();
    }

    public function getAssetList(int $userId): AssetCollection
    {
        $rawAssetList = ($this->database)
            ->getConnection()
            ->fetchAllAssociative('SELECT * FROM user_assets WHERE user_id = ?', [$userId]);

        $assets = new AssetCollection();

        foreach ($rawAssetList as $asset) {
            $assets->addAssets(new Asset(
                $asset['symbol'],
                $asset['amount'],
                $asset['average_cost']
            ));
        }

        return $assets;
    }

    public function getSingleAsset(int $userId, string $symbol): ?Asset
    {
        $rawAsset = ($this->database)
            ->getConnection()
            ->fetchAssociative('SELECT * FROM user_assets WHERE user_id = ? and symbol = ?', [$userId, $symbol]);

        if ($rawAsset) {
            return new Asset(
                $rawAsset['symbol'],
                $rawAsset['amount'],
                $rawAsset['average_cost']
            );
        }
        return null;
    }

    public function getAssetAmount(int $userId, string $symbol): float
    {
        $asset = ($this->database)
            ->getConnection()
            ->fetchAssociative('SELECT * FROM user_assets WHERE user_id = ? and symbol = ?', [$userId, $symbol]);

        return $asset['amount'] ?? 0;
    }

    public function getOldDollarCostAverage(int $userId, string $symbol): ?float
    {
        $asset = ($this->database)
            ->getConnection()
            ->fetchAssociative('SELECT average_cost FROM user_assets WHERE user_id = ? and symbol = ?', [$userId, $symbol]);

//        var_dump($asset);die;
        return $asset['average_cost'];
    }
}