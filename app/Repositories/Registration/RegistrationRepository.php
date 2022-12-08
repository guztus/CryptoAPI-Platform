<?php declare(strict_types=1);

namespace App\Repositories\Registration;

use App\Database;

class RegistrationRepository
{
    public function save(string $name, string $email, string $password)
    {
        $query = (new Database())->getConnection()->createQueryBuilder()->insert('users')->values([
            'name' => '?',
            'email' => '?',
            'password' => '?'
        ])->setParameter(0, $name)->setParameter(1, $email)->setParameter(2, $password);
        $query->executeStatement();
    }
}