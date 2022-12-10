<?php declare(strict_types=1);

namespace App\Models;

class User
{
    private ?int $id;
    private string $name;
    private string $email;
    private string $password;
    private float $fiatBalance;

    public function __construct(?int $id, string $name, string $email, string $password, float $fiatBalance)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->fiatBalance = $fiatBalance;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFiatBalance(): float
    {
        return $this->fiatBalance;
    }
}