<?php declare(strict_types=1);

namespace App;

use App\Models\Transaction;
use App\Repositories\Coins\CoinsRepository;
use App\Repositories\User\UserAssetsRepository;
use App\Services\CoinsService;
use App\Services\User\UserAssetsService;
use App\Services\User\UserService;
use App\Services\User\UserTransactionHistoryService;

class Validator
{
    private ?CoinsRepository $coinsRepository;

    public function __construct(?CoinsRepository $coinsRepository = null)
    {
        $this->coinsRepository = $coinsRepository;
    }

    public static function passed(): bool
    {
        return empty($_SESSION['errors']);
    }

    public function register(string $email, string $password, string $passwordConfirmation): void
    {
        $this->registrationEmail($email);
        $this->passwordConfirmation($password, $passwordConfirmation);
        $this->passwordRequirements($password);
    }

    public function login(string $email, string $password)
    {
        $user = (new Database())->getConnection()->fetchAssociative('SELECT * FROM users WHERE email = ?', [$email]);
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['errors']['login'] [] = 'Login failed. Please make sure that all fields are filled out correctly and try again!';
        }
    }

    public function transactionOrder(int $userId, string $symbol, string $transactionType, string $fiatAmount): Redirect
    {
        //        if ($fiatAmount < 0 || $fiatAmount !== (float)$fiatAmount) {
        if ($fiatAmount < 0 || !$fiatAmount) {
            $_SESSION['errors']['transaction'] [] = 'Transaction error';
            return Redirect::to("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        }

        $currentCoinPrice = ((new CoinsService($this->coinsRepository))->execute($symbol))->getPrice();
        $currentUser = (new UserService())->getUserData($userId);
        $userFiatBalance = $currentUser->getFiatBalance();

        // if is BUY order and user does not have enough FIAT balance
        if ($transactionType == 'buy' && $fiatAmount > $userFiatBalance) {
            $_SESSION['errors']['transaction'] [] = 'You do not have enough money to buy this amount of coins!';
            return Redirect::to("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        }

        // if is SELL order and user does not have enough COIN balance
        $currentAssetAmount = (new UserAssetsService())->getAssetAmount($userId, $symbol);
        if ($transactionType == 'sell' && $fiatAmount > $currentAssetAmount * $currentCoinPrice) {
            $_SESSION['errors']['transaction'] [] = 'You do not have enough coins to sell this amount!';
            return Redirect::to("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        }

        $assetAmount = $fiatAmount / $currentCoinPrice;
        $dollarCostAverage = $fiatAmount / $assetAmount;


        (new UserAssetsService())->modifyAssets($currentUser->getId(), $symbol, $assetAmount, $dollarCostAverage, $transactionType);
        (new UserService())->modifyFiatBalance($currentUser->getId(), (float)$fiatAmount, $transactionType);
        (new UserTransactionHistoryService())->addTransaction(
            new Transaction(
                $currentUser->getId(),
                $transactionType,
                $symbol,
                $assetAmount,
                $currentCoinPrice,
                (float)$fiatAmount,
                date('Y-m-d H:i:s')
            )
        );

        $_SESSION['alerts']['transaction'] [] = ucfirst($transactionType) . " order successful: $assetAmount $symbol for $fiatAmount USD!";

        return Redirect::to("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    }

    private function registrationEmail(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['errors']['email'] [] = 'Email is not valid';
        }

        $emailCountInSystem = (new Database())->getConnection()->executeStatement('SELECT * FROM users WHERE email = ?', [$email]);
        if ($emailCountInSystem > 0) {
            $_SESSION['errors']['email'] [] = 'Registration failed. Please check that all fields are filled out correctly and try again!';
        }
    }

    private function passwordRequirements(string $password): void
    {
        preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,}$/', $password, $matches);
        if (empty($matches[0])) {
            $_SESSION['errors']['password'] [] = 'Password must contain at least: 8 characters, uppercase letter, lowercase letter and number';
        }
    }

    private function passwordConfirmation(string $password, string $passwordConfirmation): void
    {
        if ($password !== $passwordConfirmation) {
            $_SESSION['errors']['password'] [] = 'Password confirmation does not match';
        }
    }
}