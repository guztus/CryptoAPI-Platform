<?php declare(strict_types=1);

namespace App;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class Database
{
    private static ?Connection $connection = null;

    public function getConnection(): ?Connection
    {
        if (self::$connection == null) {
            $connectionParams = [
                'dbname' => $_ENV['DATABASENAME'],
                'user' => $_ENV['DATABASEUSER'],
                'password' => $_ENV['DATABASEPASSWORD'],
                'host' => $_ENV['DATABASEHOST'],
                'driver' => $_ENV['DATABASEDRIVER'],
            ];
            self::$connection = DriverManager::getConnection($connectionParams);
        }
        return self::$connection;
    }
}