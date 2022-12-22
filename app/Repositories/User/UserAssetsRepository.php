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
    }

    public function modifyAssets(
        int    $userId,
        string $operation,
        string $symbol,
        float  $amount,
        ?float $price = 0,
        ?float $oldDollarCostAverage = 0,
        ?float $purchaseDollarCostAverage = 0
        // PRICE null when DEPOSIT / WITHDRAW / SEND
        // DOLLAR COST AVERAGE null when DEPOSIT / WITHDRAW / SEND
        // OLD DOLLAR COST AVERAGE null when buy & no assets
    ): void
    {
        if ($operation == 'short' || $operation == 'closeShort') {
            $type = 'short';
        } else {
            $type = 'standard';
        }

        // check if user has any assets of this type
        $userHasThisAsset = $this->database->createQueryBuilder()
            ->select('*')
            ->from('user_assets')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->andWhere('type = ?')
            ->setParameter(0, $userId)
            ->setParameter(1, $symbol)
            ->setParameter(2, $type)
            ->fetchAssociative();

        $previousValue = $userHasThisAsset['amount'] ?? 0;

        // if they don't, create a new asset
        if (empty($userHasThisAsset)) {
            $checkIfUserHasAsset = $this->database->createQueryBuilder()
                ->insert('user_assets')
                ->values([
                    'user_id' => '?',
                    'type' => '?',
                    'symbol' => '?',
                    'amount' => '?',
                    'average_cost' => '?'
                ])
                ->setParameter(0, $userId)
                ->setParameter(1, $type)
                ->setParameter(2, $symbol)
                ->setParameter(3, $amount)
                ->setParameter(4, $price);

            $checkIfUserHasAsset->executeQuery();
        } else {
            var_dump($oldDollarCostAverage);
            if ($operation != 'send') {
                if ($amount > 0) {
                    $newDollarCostAverage =
                        (($userHasThisAsset['amount'] * $oldDollarCostAverage) + ($purchaseDollarCostAverage * $amount))
                        / ($userHasThisAsset['amount'] + $amount) . " ";
                } else {
                    $newDollarCostAverage = $oldDollarCostAverage;
                }
            } else {
                $newDollarCostAverage = $oldDollarCostAverage;
            }

//            $sql = "UPDATE user_assets SET amount = amount +'$amount' WHERE user_id = '$userId' AND symbol = '$symbol' AND type = '$type'";
//            $this->database->executeQuery($sql);
//
//            $sql = "UPDATE user_assets SET average_cost = '$newDollarCostAverage' WHERE user_id = '$userId' AND symbol = '$symbol' AND type = '$type'";
//            $this->database->executeQuery($sql);

            $newValue = $previousValue + $amount;

            if ($newValue <= 0) {
                $this->deleteAsset($userId, $symbol, $type);
            }

            $updateAmount = $this->database->createQueryBuilder()
                ->update('user_assets')
                ->set('amount', "amount + $amount")
                ->where('user_id = ?', 'symbol = ?', 'type = ?')
                ->setParameter(0, $userId)
                ->setParameter(1, $symbol)
                ->setParameter(2, $type);

            $updateAmount->executeQuery();

            $updateAverage = $this->database->createQueryBuilder()
                ->update('user_assets')
                ->set('average_cost', "$newDollarCostAverage")
                ->where('user_id = ?', 'symbol = ?', 'type = ?')
                ->setParameter(0, $userId)
                ->setParameter(1, $symbol)
                ->setParameter(2, $type);

            $updateAverage->executeQuery();
        }
    }

    public function getAssetList(int $userId): ?AssetCollection
    {
        $rawAssetList = $this->database->createQueryBuilder()
            ->select('*')
            ->from('user_assets')
            ->where('user_id = ?')
            ->setParameter(0, $userId)
            ->fetchAllAssociative();

        if (empty($rawAssetList)) {
            return null;
        }

        $assetList = new AssetCollection();

        foreach ($rawAssetList as $asset) {
            $assetSymbol = $this->coinsRepository->getBySymbol($asset['symbol']);

            // if is not a valid symbol, skip it ...
            if (!$assetSymbol) {
                continue;
            }

            $assetList->addAsset(new Asset(
                $asset['symbol'],
                $asset['type'],
                (float)$asset['amount'],
                (float)$asset['average_cost'],
                $assetSymbol->getPrice()
            ));
        }

        return $assetList;
    }

    public function getSingleAsset(int $userId, string $symbol, ?string $type = 'standard'): ?Asset
    {
        $rawAsset = $this->database->createQueryBuilder()
            ->select('*')
            ->from('user_assets')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->andWhere('type = ?')
            ->setParameter(0, $userId)
            ->setParameter(1, $symbol)
            ->setParameter(2, $type)
            ->fetchAssociative();

        if ($rawAsset) {
            return new Asset(
                $rawAsset['symbol'],
                $rawAsset['type'],
                (float)$rawAsset['amount'],
                (float)$rawAsset['average_cost']
            );
        }
        return null;
    }

    public function getAssetAmount(int $userId, string $symbol, ?string $type = 'standard'): float
    {
        if ($type == null) {
            $type = 'standard';
        }

        $asset = $this->database->createQueryBuilder()
            ->select('amount')
            ->from('user_assets')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->andWhere('type = ?')
            ->setParameter(0, $userId)
            ->setParameter(1, $symbol)
            ->setParameter(2, $type)
            ->fetchAssociative();

        return (float)$asset['amount'] ?? 0;
    }

    public function getOldDollarCostAverage(int $userId, string $symbol, ?string $type = 'standard'): ?float
    {
        $asset = $this->database->createQueryBuilder()
            ->select('average_cost')
            ->from('user_assets')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->andWhere('type = ?')
            ->setParameter(0, $userId)
            ->setParameter(1, $symbol)
            ->setParameter(2, $type)
            ->fetchAssociative();

        if (is_null($asset['average_cost'])) {
            return null;
        }

        return (float)$asset['average_cost'];
    }

    private function deleteAsset(
        int     $userId,
        string  $symbol,
        ?string $type = 'standard'
    )
    {
        if ($type == null) {
            $type = 'standard';
        }

        $query = $this->database->createQueryBuilder()
            ->delete('user_assets')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->andWhere('type = ?')
            ->setParameter(0, $userId)
            ->setParameter(1, $symbol)
            ->setParameter(2, $type);
        $query->executeQuery();
    }
}