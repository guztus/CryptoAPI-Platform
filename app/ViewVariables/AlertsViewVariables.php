<?php declare(strict_types=1);

namespace App\ViewVariables;

class AlertsViewVariables implements ViewVariablesInterface
{
    public function getName(): string
    {
        return 'alerts';
    }

    public function getValue(): array
    {
        return $_SESSION['alerts'] ?? [];
    }
}