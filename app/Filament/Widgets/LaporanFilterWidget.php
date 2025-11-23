<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;

class LaporanFilterWidget extends Widget
{
    protected string $view = 'filament.widgets.laporan-filter-widget';
    protected int|string|array $columnSpan = 10;
    // State
    public ?string $periode = 'bulanan';
    public ?string $selectedCategory = null;
    public ?string $selectedType = null;
    public ?string $start_date = null;
    public ?string $end_date = null;

    #[On('refreshFilters')]
    public function refreshFilters()
    {
        $this->dispatch('filtersUpdated', [
            'periode' => $this->periode,
            'type' => $this->selectedType,
            'category' => $this->selectedCategory,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);
    }
}
