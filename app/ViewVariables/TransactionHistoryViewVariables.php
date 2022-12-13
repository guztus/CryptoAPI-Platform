<?php declare(strict_types=1);

namespace App\ViewVariables;

use App\Services\User\UserTransactionHistoryService;

class TransactionHistoryViewVariables implements ViewVariablesInterface
{
    public function getName(): string
    {
        return 'transactionHistory';
    }

    public function getValue(): array
    {
        if (!empty($_SESSION['auth_id'])) {
            $transactionHistory = (new UserTransactionHistoryService())->getTransactionHistory($_SESSION['auth_id']);

            if ($transactionHistory) {
                return [
                    'list' => $transactionHistory->all() ?? null,
                ];
            }
        }
        return [];
    }
}