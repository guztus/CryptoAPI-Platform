<?php declare(strict_types=1);

namespace App\Controllers;

use App\Redirect;
use App\Repositories\Coins\CoinsRepository;
use App\Services\PortfolioService;
use App\Template;

class PortfolioController
{
    private CoinsRepository $coinsRepository;

    public function __construct(CoinsRepository $coinsRepository)
    {
        $this->coinsRepository = $coinsRepository;
    }

    public function show()
    {
        if (empty($_SESSION['auth_id'])) {
            return Redirect::to('/login');
        }

        $portfolioData = (new PortfolioService($this->coinsRepository))->execute();

        return Template::render('portfolio/portfolio.view.twig', ['displayData' => $portfolioData ?? []]);
    }
}