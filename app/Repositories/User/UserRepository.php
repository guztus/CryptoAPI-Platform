<?php declare(strict_types=1);

namespace App\Repositories\User;

use App\Database;
use App\Models\User;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Connection;

class UserRepository
{
    private ?Connection $connection;
    private QueryBuilder $queryBuilder;

    public function __construct()
    {
        $this->connection = (new Database())->getConnection();
        $this->queryBuilder = $this->connection->createQueryBuilder();
    }

    public function getUserData(
        ?int    $id = null,
        ?string $email = null
    ): ?User
    {
        if ($id !== null) {
            return $this->getUserById($id);
        }
        if ($email !== null) {
            return $this->getUserByEmail($email);
        }
        return null;
    }

    public function modifyFiatBalance(
        int    $id,
        float  $fiatAmount,
        string $operation
    ): void
    {
        if ($operation == 'sell' || $operation == 'deposit') {
            $operator = '+';
        } else {
            $operator = '-';
        }

//        var_dump($fiatAmount);die;
        $query = $this->queryBuilder
            ->update('users')
            ->set('fiat_balance', "fiat_balance $operator $fiatAmount")
            ->where('id = ?')
            ->setParameter(0, $id);
        $query->executeStatement();
    }

    private function getUserById(
        ?int $id
    ): ?User
    {
        if ($id == null) {
            return null;
        }
        $user = $this->queryBuilder
            ->select('*')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->fetchAssociative();

        return new User(
            (int)$user['id'],
            $user['name'],
            $user['email'],
            $user['password'],
            (float)$user['fiat_balance']
        );
    }

    private function getUserByEmail(
        string $email
    ): ?User
    {
        $user = $this->queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $email)
            ->fetchAssociative();

        if (!$user) {
            return null;
        }
        return new User(
            (int)$user['id'],
            $user['name'],
            $user['email'],
            $user['password'],
            (float)$user['fiat_balance']
        );
    }
}