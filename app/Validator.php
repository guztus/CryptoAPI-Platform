<?php declare(strict_types=1);

namespace App;

use App\Repositories\Coins\CoinsRepository;
use App\Repositories\User\UserAssetsRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

class Validator
{
    private ?Connection $connection;
    private QueryBuilder $queryBuilder;

    public function __construct()
    {
        $this->connection = (new Database())->getConnection();
        $this->queryBuilder = $this->connection->createQueryBuilder();
    }

    public static function passed(): bool
    {
        return empty($_SESSION['errors']);
    }

    public function register(
        string $email,
        string $password,
        string $passwordConfirmation
    ): void
    {
        $this->registrationEmail($email);
        $this->passwordConfirmation($password, $passwordConfirmation);
        $this->passwordRequirements($password);
    }

    public function login(
        string $email,
        string $password
    ): void
    {
        $user = $this->queryBuilder->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $email)
            ->fetchAssociative();

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['errors']['login'] [] =
                'Login failed. Please make sure that all fields are filled out correctly and try again!';
        }
    }

    public function buySellTransaction(
        int    $userId,
        string $transactionType,
        string $symbol,
        float  $fiatAmount,
        float  $currentCoinPrice,
        float $userFiatBalance
    )
    {
        $this->transactionValue($fiatAmount);
        // if is SELL order and user does not have enough COIN balance
        $currentAssetAmount = (new UserAssetsRepository())->getAssetAmount($userId, $symbol);
        if ($transactionType == 'sell' && $fiatAmount > $currentAssetAmount * $currentCoinPrice) {
            $_SESSION['errors']['transaction'] [] = 'You do not have enough coins to sell this amount!';
        }

        $this->transactionValue($fiatAmount);
        // if is BUY order and user does not have enough FIAT balance
        if ($transactionType == 'buy' && $fiatAmount > $userFiatBalance) {
            $_SESSION['errors']['transaction'] [] = 'You do not have enough money to buy this amount of coins!';
        }
    }

    private function transactionValue(
        $fiatAmount
    )
    {
        if ($fiatAmount < 0 || !$fiatAmount) {
            $_SESSION['errors']['transaction'] [] = 'Transaction error';
        }
    }

    private function registrationEmail(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['errors']['email'] [] =
                'Email is not valid';
        }

        $emailCountInSystem = $this->queryBuilder->select('COUNT(*)')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $email)
            ->fetchOne();

        if ($emailCountInSystem > 0) {
            $_SESSION['errors']['email'] [] =
                'Registration failed. Please check that all fields are filled out correctly and try again!';
        }
    }

    private function passwordRequirements(string $password): void
    {
        preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,}$/', $password, $matches);
        if (empty($matches[0])) {
            $_SESSION['errors']['password'] [] =
                'Password must contain at least: 8 characters, uppercase letter, lowercase letter and number';
        }
    }

    private function passwordConfirmation(string $password, string $passwordConfirmation): void
    {
        if ($password !== $passwordConfirmation) {
            $_SESSION['errors']['password'] [] =
                'Password confirmation does not match';
        }
    }
}