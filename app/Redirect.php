<?php declare(strict_types=1);

namespace App;

class Redirect
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public static function to(string $path): Redirect
    {
        return new Redirect($path);
    }
}