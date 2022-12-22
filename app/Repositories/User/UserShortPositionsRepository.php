<?php
//
//namespace App\Repositories\User;
//
//use App\Database;
//use App\Repositories\Coins\CoinsRepository;
//use Doctrine\DBAL\Connection;
//use Doctrine\DBAL\Query\QueryBuilder;
//
//class UserShortPositionsRepository
//{
//    private ?CoinsRepository $coinsRepository;
//    private ?Connection $database;
//    private QueryBuilder $queryBuilder;
//
//    public function __construct(?CoinsRepository $coinsRepository = null)
//    {
//        $this->coinsRepository = $coinsRepository;
//        $this->database = (new Database())->getConnection();
//        $this->queryBuilder = $this->database->createQueryBuilder();
//    }
//
//    public function openPosition(
//        int    $userId,
//        string $type,
//        string $symbol,
//        float  $amount,
//        float  $openingPrice
//    ): void
//    {
//        $query = $this->queryBuilder
//            ->insert('coin_positions')
//            ->values([
//                'user_id' => '?',
//                'type' => '?',
//                'symbol' => '?',
//                'amount' => '?',
//                'opening_price' => '?',
//                'opening_fiat_amount' => '?',
//                'closed_fiat_amount' => '?',
//                'is_open' => '?',
//                'timestamp' => '?',
//            ])
//            ->setParameter(0, $userId)
//            ->setParameter(1, $type)
//            ->setParameter(2, $symbol)
//            ->setParameter(3, $amount)
//            ->setParameter(4, $openingPrice)
//            ->setParameter(5, $openingPrice * $amount)
//            ->setParameter(6, null)
//            ->setParameter(7, true)
//            ->setParameter(8, date('Y-m-d H:i:s'));
//        $query->executeQuery();
//    }
//
////    function for updating position
//    public function updatePosition(
//        int    $userId,
//        string $type,
//        string $symbol,
//        float  $amount,
//        float  $closedFiatAmount
//    ): void
//    {
//        $query = $this->queryBuilder
//            ->update('coin_positions')
//            ->set('amount', 'amount + ?')
//            ->set('closed_fiat_amount', 'closed_fiat_amount + ?')
//            ->where('user_id = ?')
//            ->andWhere('type = ?')
//            ->andWhere('symbol = ?')
//            ->setParameter(0, $amount)
//            ->setParameter(1, $closedFiatAmount)
//            ->setParameter(2, $userId)
//            ->setParameter(3, $type)
//            ->setParameter(4, $symbol);
//        $query->executeQuery();
//    }
//
//    public function closePosition(
//        int    $userId,
//        string $type,
//        string $symbol,
//        float  $amount,
//        float  $closingPrice
//    ): void
//    {
//        $query = $this->queryBuilder
//            ->update('coin_positions')
//            ->set('is_open', '?')
//            ->set('closing_price', '?')
//            ->set('closing_fiat_amount', '?')
//            ->set('closed_fiat_amount', '?') // check out how
//            ->set('timestamp', '?')
//            ->where('user_id = ?')
//            ->andWhere('type = ?')
//            ->andWhere('symbol = ?')
//            ->andWhere('is_open = ?')
//            ->setParameter(0, false)
//            ->setParameter(1, $closingPrice)
//            ->setParameter(2, $closingPrice * $amount)
//            ->setParameter(3, $closingPrice * $amount) // not this
//            ->setParameter(4, date('Y-m-d H:i:s'))
//            ->setParameter(5, $userId)
//            ->setParameter(6, $type)
//            ->setParameter(7, $symbol)
//            ->setParameter(8, true);
//        $query->executeQuery();
//    }
//
//    public function getShortPositions(int $userId): array
//    {
//        $shortPositions = $this->queryBuilder
//            ->select('*')
//            ->from('coin_positions')
//            ->where('user_id = ?')
//            ->setParameter(0, $userId)
//            ->fetchAllAssociative();
//
//        return $shortPositions;
//    }
//}