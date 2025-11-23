<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DateRangeFilterWidget extends Widget
{
    protected string $view = 'filament.widgets.date-range-filter-widget';

    public ?string $start = null;
    public ?string $end = null;

    public function updatedStart()
    {
        $this->setDateRange(
            [
                'start' => $this->start
            ]
        );
    }

    public function updatedEnd()
    {
        $this->setDateRange(
            [
                'end' => $this->end
            ]
        );
    }

    
public function setDateRange($range)
{
    $this->start_date = $range['start'];
    $this->end_date = $range['end'];
    $this->dispatch('$refresh');
}
}
