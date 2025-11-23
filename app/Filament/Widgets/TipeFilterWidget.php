<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class TipeFilterWidget extends Widget
{
    protected string $view = 'filament.widgets.tipe-filter-widget';

    public ?string $type = null;

    public function setType($type)
    {
        $this->selectedType = $type;
        $this->dispatch('$refresh');
    }
}
