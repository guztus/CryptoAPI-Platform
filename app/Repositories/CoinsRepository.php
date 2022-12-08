<?php

namespace App\Repositories;

use App\Models\Collections\CoinCollection;

interface CoinsRepository
{
    public function getList(): CoinCollection;
}