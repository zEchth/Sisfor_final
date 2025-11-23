<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables;

class Laporan extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = 'Laporan Keuangan';
    
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\LaporanFilterWidget::class,
            // \App\Filament\Widgets\PeriodeFilterWidget::class,
            // \App\Filament\Widgets\KategoriFilterWidget::class,
            // \App\Filament\Widgets\TipeFilterWidget::class,
            // \App\Filament\Widgets\DateRangeFilterWidget::class,
            \App\Filament\Widgets\LaporanStatsWidget::class,
            \App\Filament\Widgets\LaporanTableWidget::class,
        ];
    }
    
    public function getColumns(): int|array
    {
        return 12;
    }
}
