<?php //declare(strict_types=1);
//
//namespace App\ViewVariables;
//
//use App\Repositories\User\Transaction\UserTransactionHistoryRepository;
//
//class TransactionHistoryViewVariables implements ViewVariablesInterface
//{
//    public function getName(): string
//    {
//        return 'transactionHistory';
//    }
//
//    public function getValue(): array
//    {
////        if (!empty($_SESSION['auth_id'])) {
////
////            $transactionHistory = (new UserTransactionHistoryRepository())
////                ->getHistory($_SESSION['auth_id']);
////
////            return [
////                'list' => $transactionHistory->all() ?? [],
////            ];
////        }
//        return [];
//    }
//}