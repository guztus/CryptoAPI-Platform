<?php declare(strict_types=1);

namespace App\Services;

use App\Repositories\Coins\CoinsRepository;

class CoinsService
{
    private CoinsRepository $coinsRepository;

    public function __construct(CoinsRepository $coinsRepository)
    {
        $this->coinsRepository = $coinsRepository;
    }

    public function execute(?string $coinSymbol = null)
    {
        if ($coinSymbol) {
            return $this->coinsRepository->getBySymbol($coinSymbol);
        }
        return $this->coinsRepository->getList();
    }
}