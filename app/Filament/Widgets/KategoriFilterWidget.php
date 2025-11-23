<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class KategoriFilterWidget extends Widget
{
    protected string $view = 'filament.widgets.kategori-filter-widget';

    public ?string $category = null;

    public function setCategory($category)
    {
        $this->selectedCategory = $category;
        // $this->dispatch('$refresh');
        $this->resetTable();
    }
}
