<?php declare(strict_types=1);

namespace App\ViewVariables;

use App\Services\User\Transaction\UserGetTransactionHistoryService;

class TransactionHistoryViewVariables implements ViewVariablesInterface
{
    public function getName(): string
    {
        return 'transactionHistory';
    }

    public function getValue(): array
    {
        if (!empty($_SESSION['auth_id'])) {

            $transactionHistory = (new UserGetTransactionHistoryService())->execute($_SESSION['auth_id']);

            return [
                'list' => $transactionHistory->all() ?? [],
            ];
        }
        return [];
    }
}