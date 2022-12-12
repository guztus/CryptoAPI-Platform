<?php declare(strict_types=1);

namespace App\Models;

class Coin
{
    private int $id;
    private string $name;
    private string $symbol;
    private string $dateAdded;
    private ?float $maxSupply;
    private float $circulatingSupply;
    private float $totalSupply;
    private string $lastUpdated;
    private float $price;
    private float $volume24h;
    private float $volumeChange24h;
    private float $percentChange24h;
    private float $marketCap;

    public function __construct(
        int    $id,
        string $name,
        string $symbol,
        string $dateAdded,
        ?float  $maxSupply,
        float  $circulatingSupply,
        float  $totalSupply,
        string $lastUpdated,
        float  $price,
        float  $volume24h,
        float  $volumeChange24h,
        float  $percentChange24h,
        float  $marketCap
    )
    {

        $this->id = $id;
        $this->name = $name;
        $this->symbol = $symbol;
        $this->dateAdded = $dateAdded;
        $this->maxSupply = $maxSupply ?? null;
        $this->circulatingSupply = $circulatingSupply;
        $this->totalSupply = $totalSupply;
        $this->lastUpdated = $lastUpdated;
        $this->price = $price;
        $this->volume24h = $volume24h;
        $this->volumeChange24h = $volumeChange24h;
        $this->percentChange24h = $percentChange24h;
        $this->marketCap = $marketCap;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getDateAdded(): string
    {
        return $this->dateAdded;
    }

    public function getMaxSupply(): ?float
    {
        return $this->maxSupply;
    }

    public function getCirculatingSupply(): float
    {
        return $this->circulatingSupply;
    }

    public function getTotalSupply(): float
    {
        return $this->totalSupply;
    }

    public function getLastUpdated(): string
    {
        return $this->lastUpdated;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getVolume24h(): float
    {
        return $this->volume24h;
    }

    public function getVolumeChange24h(): float
    {
        return $this->volumeChange24h;
    }

    public function getPercentChange24h(): float
    {
        return $this->percentChange24h;
    }

    public function getMarketCap(): float
    {
        return $this->marketCap;
    }

}