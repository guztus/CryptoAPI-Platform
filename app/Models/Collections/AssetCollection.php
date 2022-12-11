<?php declare(strict_types=1);

namespace App\Models\Collections;

use App\Models\Asset;

class AssetCollection
{
    private array $assets = [];

    public function addAssets(Asset ...$assets)
    {
        $this->assets = array_merge($this->assets, $assets);
    }

    public function getAllAssets(): array
    {
        return $this->assets;
    }
}