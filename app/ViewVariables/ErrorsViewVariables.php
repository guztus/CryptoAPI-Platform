<?php declare(strict_types=1);

namespace App\ViewVariables;

class ErrorsViewVariables implements ViewVariablesInterface
{
    public function getName(): string
    {
        return 'errors';
    }

    public function getValue(): array
    {
        return $_SESSION['errors'] ?? [];
    }
}