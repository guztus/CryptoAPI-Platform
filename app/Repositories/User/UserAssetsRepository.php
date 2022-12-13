<?php declare(strict_types=1);

namespace App\Repositories\User;

use App\Database;
use App\Models\Asset;
use App\Models\Collections\AssetCollection;
use App\Repositories\Coins\CoinsRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

class UserAssetsRepository
{
    private ?CoinsRepository $coinsRepository;
    private ?Connection $database;
    private QueryBuilder $queryBuilder;

    public function __construct(?CoinsRepository $coinsRepository = null)
    {
        $this->coinsRepository = $coinsRepository;
        $this->database = (new Database())->getConnection();
        $this->queryBuilder = $this->database->createQueryBuilder();
    }

    public function modifyAssets(
        int    $userId,
        string $symbol,
        float  $amount,
        float  $dollarCostAverage,
        string $operation
    ): void
    {
        $userHasThisAsset = $this->queryBuilder
            ->select('*')
            ->from('user_assets')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->setParameter(0, $userId)
            ->setParameter(1, $symbol)
            ->fetchAllAssociative();

        if (empty($userHasThisAsset)) {
            $query = $this->queryBuilder
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
            $oldDollarCostAverage = $this->getOldDollarCostAverage($userId, $symbol);

            if ($operation == 'sell') {
                $operator = '-';
                $currentDollarCostAverage = $oldDollarCostAverage;
            } else {
                $operator = '+';
                $currentDollarCostAverage = ($oldDollarCostAverage + $dollarCostAverage) / 2;
            }

            $query = $this->queryBuilder
                ->update('user_assets')
                ->set('amount', "amount $operator $amount")
                ->set('average_cost', $currentDollarCostAverage)
                ->where("user_id = $userId", "symbol = '$symbol'");
        }
        $query->executeStatement();
    }

    public function getAssetList(int $userId): AssetCollection
    {
        $rawAssetList = $this->queryBuilder
            ->select('*')
            ->from('user_assets')
            ->where('user_id = ?')
            ->setParameter(0, $userId)
            ->fetchAllAssociative();

        $assetList = new AssetCollection();

        foreach ($rawAssetList as $asset) {
            $assetSymbol = $this->coinsRepository->getBySymbol($asset['symbol']);

            // if is not a valid symbol, skip it ...
            if (!$assetSymbol) {
                continue;
            }

            $assetList->addAsset(new Asset(
                $asset['symbol'],
                (float)$asset['amount'],
                (float)$asset['average_cost'],
                $assetSymbol->getPrice()
            ));
        }

        return $assetList;
    }

    public function getSingleAsset(int $userId, string $symbol): ?Asset
    {
        $rawAsset = $this->queryBuilder
            ->select('*')
            ->from('user_assets')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->setParameter(0, $userId)
            ->setParameter(1, $symbol)
            ->fetchAssociative();

        if ($rawAsset) {
            return new Asset(
                $rawAsset['symbol'],
                (float)$rawAsset['amount'],
                (float)$rawAsset['average_cost']
            );
        }
        return null;
    }

    public function getAssetAmount(int $userId, string $symbol): float
    {
        $asset = $this->queryBuilder
            ->select('amount')
            ->from('user_assets')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->setParameter(0, $userId)
            ->setParameter(1, $symbol)
            ->fetchAssociative();

        return (float)$asset['amount'] ?? 0;
    }

    public function getOldDollarCostAverage(int $userId, string $symbol): ?float
    {
        $asset = $this->queryBuilder
            ->select('average_cost')
            ->from('user_assets')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->setParameter(0, $userId)
            ->setParameter(1, $symbol)
            ->fetchAssociative();

        return (float)$asset['average_cost'];
    }
}