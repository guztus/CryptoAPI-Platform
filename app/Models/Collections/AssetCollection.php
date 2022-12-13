<?php declare(strict_types=1);

namespace App\Models\Collections;

use App\Models\Asset;

class AssetCollection
{
    private array $assets = [];

    public function __construct(array $assets = [])
    {
        foreach ($assets as $asset) {
            $this->addAsset($asset);
        }
    }

    public function addAsset (Asset $asset): void
    {
        $this->assets [] = $asset;
    }

    public function getAllAssets(): array
    {
        return $this->assets;
    }
}