<?php

namespace App;

class TransactionIdGenerator
{
    public static function get(): int
    {
        return rand(0, 10000);
    }
}