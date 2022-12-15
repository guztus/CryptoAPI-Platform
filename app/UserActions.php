<?php

namespace App;

use App\Models\Transaction;
use App\Repositories\User\UserRepository;

class UserActions
{
    private static ?int $userId = null;

    public function __construct()
    {
        $this->setUserId();
    }

    public function withdraw()
    {

    }

    private function setUserId(): void
    {
        if (self::$userId) {
            return;
        }
        if ($_SESSION['auth_id']) {
            self::$userId = $_SESSION['auth_id'];
        }
    }
}