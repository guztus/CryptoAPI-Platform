<?php declare(strict_types=1);

namespace App\Services\User\Transaction;

use App\Models\Transaction;
use App\Repositories\Coins\CoinsRepository;
use App\Repositories\User\Transaction\UserTransactionHistoryRepository;
use App\Repositories\User\UserAssetsRepository;
use App\Repositories\User\UserRepository;
use App\Services\CoinsService;
use App\Services\User\UserGetInformationService;
use App\TransactionIdGenerator;
use App\Validator;

class UserDoTransactionService
{
    private CoinsRepository $coinsRepository;

    public function __construct(CoinsRepository $coinsRepository)
    {
        $this->coinsRepository = $coinsRepository;
    }

    public function execute(
        int    $userId,
        string $transactionType,
        string $symbol,
        ?float $fiatAmount,
        ?float $assetAmount
        // AMOUNT null when DEPOSIT / WITHDRAW
        // PRICE & FIAT AMOUNT null when SEND
    ): void
    {
        if ($transactionType === 'buy' || $transactionType === 'sell') {

            $currentCoinPrice = ((new CoinsService($this->coinsRepository))->execute($symbol))->getPrice();
            $currentUser = (new UserGetInformationService())->execute($userId);
            $userFiatBalance = $currentUser->getFiatBalance();
            $assetAmount = $fiatAmount / $currentCoinPrice;

            (new Validator())->buySellTransaction(
                $userId,
                $transactionType,
                $symbol,
                (float)$fiatAmount,
                $currentCoinPrice,
                $userFiatBalance,
            );

            if (!Validator::passed()) {
                return;
            }

            // if all is ok, create transaction & modify assets
            $_SESSION['alerts']['transaction'] [] =
                ucfirst($transactionType) . " order successful: $assetAmount $symbol for $fiatAmount USD!";
        }

        $transaction = new Transaction(
            TransactionIdGenerator::get(),
            $userId,
            $transactionType,
            $symbol,
            $assetAmount ?? null,
            $currentCoinPrice ?? null,
            $fiatAmount,
            date('Y-m-d H:i:s')
        );

        (new UserTransactionHistoryRepository())
            ->addTransaction($transaction);

//        if transaction type is buy or sell, we need to update user's ASSETS and BALANCE
        if (
            $transactionType == 'buy' ||
            $transactionType == 'sell'
        ) {
            $purchaseDollarCostAverage = $fiatAmount / $assetAmount;
            $oldDollarCostAverage = (new UserAssetsRepository())->getOldDollarCostAverage($userId, $symbol);

            (new UserAssetsRepository())
                ->modifyAssets(
                    $userId,
                    $transactionType,
                    $symbol,
                    $assetAmount,
                    $currentCoinPrice,
                    $oldDollarCostAverage,
                    $purchaseDollarCostAverage
                );
            (new UserRepository())
                ->modifyFiatBalance(
                    $transaction->getUserId(),
                    $transaction->getOrderSum(),
                    $transaction->getTransactionType()
                );
        } //        if transaction type is deposit or withdraw, we need to update user's BALANCE
        else if (
            $transactionType == 'deposit' ||
            $transactionType == 'withdraw'
        ) {
            (new UserRepository())
                ->modifyFiatBalance(
                    $transaction->getUserId(),
                    $transaction->getOrderSum(),
                    $transaction->getTransactionType()
                );
        } //        if transaction type is send or receive we need to update users ASSETS
        else if (
            $transactionType == 'send' ||
            $transactionType == 'receive'
        ) {
            (new UserAssetsRepository())
                ->modifyAssets(
                    $userId,
                    $transactionType,
                    $symbol,
                    $assetAmount,
                    null,
                    null,
                    null
                );
        }
    }
}
