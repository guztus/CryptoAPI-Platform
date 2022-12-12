<?php

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

//            echo "<pre>";
//            var_dump($transactionHistory);die;
            if ($transactionHistory) {
                return [
                    'list' => $transactionHistory ?? null,
                ];
            }
        }
        return [];
    }
}