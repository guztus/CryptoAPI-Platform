<?php declare(strict_types=1);

namespace App\Repositories\User;

use App\Database;
use App\Models\User;

class UserRepository
{
    public function getUserData(?int $id = null, ?string $email = null): ?User
    {

        if ($id !== null) {
            return $this->getUserById($id);
        }
        if ($email !== null) {
            return $this->getUserByEmail($email);
        }
        return null;
    }

    public function modifyFiatBalance(int $id, float $fiatAmount, string $operation)
    {
        if ($operation == 'sell' || $operation == 'deposit') {
            $operator = '+';
        } else {
            $operator = '-';
        }
        $query = (new Database())->getConnection()->createQueryBuilder()->update('users')->set('fiat_balance', "fiat_balance $operator $fiatAmount")->where($id);
        $query->executeStatement();
    }

    private function getUserById(?int $id): ?User
    {
        if ($id == null) {
            return null;
        }
        $user = (new Database())->getConnection()->fetchAssociative('SELECT * FROM users WHERE id = ?', [$id]);
        return new User((int)$user['id'], $user['name'], $user['email'], $user['password'], (float)$user['fiat_balance']);
    }

    private function getUserByEmail(string $email): ?User
    {

        $user = (new Database())->getConnection()->fetchAssociative('SELECT * FROM users WHERE email = ?', [$email]);
        if (!$user) {
            return null;
        }
        return new User((int)$user['id'], $user['name'], $user['email'], $user['password'], (float)$user['fiat_balance']);
    }
}