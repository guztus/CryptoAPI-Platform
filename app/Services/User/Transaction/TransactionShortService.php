<?php
//
//namespace App\Services\User\Transaction;
//
//use App\Models\Transaction;
//use App\Repositories\Coins\CoinsRepository;
//use App\Repositories\User\Transaction\UserTransactionHistoryRepository;
//use App\Repositories\User\UserAssetsRepository;
//use App\Repositories\User\UserRepository;
//use App\Services\CoinsService;
//use App\Services\User\UserGetInformationService;
//use App\TransactionIdGenerator;
//
//class TransactionShortService
//{
//    private CoinsRepository $coinsRepository;
//
//    public function __construct(CoinsRepository $coinsRepository)
//    {
//        $this->coinsRepository = $coinsRepository;
//    }
//
//    public function execute(
//        int    $userId,
//        string $transactionType,
//        string $symbol,
//        float  $amount
//    )
//    {
//        $currentCoinPrice = ((new CoinsService($this->coinsRepository))->execute($symbol))->getPrice();
//        $currentUser = (new UserGetInformationService())->execute($userId);
//        $fiatAmount = $currentCoinPrice * $amount;
//
//        $transaction = new Transaction(
//            TransactionIdGenerator::get(),
//            $userId,
//            $transactionType."(open)",
//            $symbol,
//            $amount ?? null,
//            $currentCoinPrice ?? null,
//            $fiatAmount,
//            date('Y-m-d H:i:s')
//        );
//
//        (new UserTransactionHistoryRepository())
//            ->add($transaction);
//
//        // open a short position -
//        // modify assets in short positions
//        //
//
////        (new UserShortPositionsRepository())
////            ->modifyAssets(
////                $transaction->getUserId(),
////                $transaction->getTransactionType(),
////                $transaction->getSymbol(),
////                $transaction->getAmount(),
////                $transaction->getPrice(),
////                $oldDollarCostAverage,
////                $purchaseDollarCostAverage
////            );
//
////        (new UserRepository())
////            ->modifyFiatBalance(
////                $transaction->getUserId(),
////                $transaction->getOrderSum(),
////                $transaction->getTransactionType()
////            );
//    }
//}