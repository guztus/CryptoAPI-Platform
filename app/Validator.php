<?php declare(strict_types=1);

namespace App;

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

    public function checkPasswordById(int $id, string $password)
    {
        $user = $this->queryBuilder->select('*')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->fetchAssociative();

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['errors']['password'] [] =
                'Entered password is incorrect!';
        }
    }

    public function buySellTransaction(
        int     $userId,
        string  $transactionType,
        string  $symbol,
        ?string $assetType,
        float   $fiatAmount,
        float   $assetAmount,
        float   $currentCoinPrice,
        float   $userFiatBalance
    )
    {
        if ($transactionType !== 'buy'
            && $transactionType !== 'sell'
            && $transactionType !== 'closeShort'
            && $transactionType !== 'short') {
            $_SESSION['errors']['transactionType'] [] =
                'Transaction type is not valid!';
        }

        $this->transactionValue($fiatAmount, $assetAmount);
        // if is SELL or BUY order and user does not have enough COIN balance
        $currentAssetAmount = (new UserAssetsRepository())->getAssetAmount($userId, $symbol, $assetType);

        if (
            ($transactionType == 'sell' || $transactionType == 'closeShort')
            && $assetAmount > $currentAssetAmount
        ) {
            $_SESSION['errors']['transaction'] [] =
                'You do not have enough coins to spend this amount!';
        }

        // if is BUY or SHORT order and user does not have enough FIAT balance
        if (($transactionType == 'buy' || $transactionType == 'short')
            && $fiatAmount > $userFiatBalance) {
            $_SESSION['errors']['transaction'] [] =
                'You do not have enough money to purchase this amount of coins!';
        }
    }

    public function transactionValue(
        float $fiatAmount,
        ?float $assetAmount = 1
    )
    {
        if ($fiatAmount < 0
            || !$fiatAmount
            || $assetAmount < 0) {
            $_SESSION['errors']['transaction'] [] =
                'Transaction error';
        }
    }

    public function assetAmount(int $userId, string $symbol, int $amount)
    {
        $currentAssetAmount = (new UserAssetsRepository())->getAssetAmount($userId, $symbol);
        if ($amount > $currentAssetAmount) {
            $_SESSION['errors']['transaction'] [] =
                "You do not have enough $symbol to send this amount!";
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