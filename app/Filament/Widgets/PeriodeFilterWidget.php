<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class PeriodeFilterWidget extends Widget
{
    protected string $view = 'filament.widgets.periode-filter-widget';

    public ?string $periode = 'bulanan';

    public function setPeriode($periode)
    {
        $this->periode = $periode;
        // $this->dispatch('$refresh');
        $this->resetTable();
    }
}
