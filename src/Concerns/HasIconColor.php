<?php

namespace Filament\Notifications\Concerns;

use Filament\Support\Concerns\HasIconColor as BaseTrait;

trait HasIconColor
{
    use BaseTrait {
        getIconColor as getBaseIconColor;
    }

    public function getIconColor(): ?string
    {
        return $this->getBaseIconColor() ?? $this->getStatus();
    }
}
