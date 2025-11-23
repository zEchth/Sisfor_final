<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;

class LaporanStatsWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 12;

    public ?string $periode = 'bulanan';
    public ?string $selectedCategory = null;
    public ?string $selectedType = null;
    public ?string $start_date = null;
    public ?string $end_date = null;

    # ============================================================
    # LISTENER FILTER
    # ============================================================

    #[On('periodeUpdated')]
    public function setPeriode($periode)
    {
        $this->periode = $periode;
        $this->dispatch('$refresh');
    }

    #[On('categoryUpdated')]
    public function setCategory($category)
    {
        $this->selectedCategory = $category;
        $this->refresh();
    }

    #[On('typeUpdated')]
    public function setType($type)
    {
        $this->selectedType = $type;
        $this->refresh();
    }

    #[On('dateUpdated')]
    public function setDateRange($range)
    {
        $this->start_date = $range['start'];
        $this->end_date   = $range['end'];
        $this->refresh();
    }

    #[On('filtersUpdated')]
    public function applyFilters($data)
    {
        $this->periode          = $data['periode'];
        $this->selectedType     = $data['type'];
        $this->selectedCategory = $data['category'];
        $this->start_date       = $data['start_date'];
        $this->end_date         = $data['end_date'];

        $this->dispatch('$refresh');
    }

    # ============================================================
    # QUERY STAT DIAMBIL DARI FILTER
    # ============================================================

    protected function baseQuery()
    {
        $query = Transaction::whereNull('deleted_at');

        if ($this->periode === 'harian') {
            $query->whereDate('transaction_date', today());
        } elseif ($this->periode === 'bulanan') {
            $query->whereMonth('transaction_date', now()->month)
                  ->whereYear('transaction_date', now()->year);
        } elseif ($this->periode === 'tahunan') {
            $query->whereYear('transaction_date', now()->year);
        } elseif ($this->periode === 'custom') {
            if ($this->start_date && $this->end_date) {
                $query->whereBetween('transaction_date', [
                    Carbon::parse($this->start_date),
                    Carbon::parse($this->end_date),
                ]);
            }
        }

        if ($this->selectedCategory) {
            $query->where('transaction_category_id', $this->selectedCategory);
        }

        if ($this->selectedType) {
            $query->where('transaction_type', $this->selectedType);
        }

        return $query;
    }

    # ============================================================
    # STATS UI
    # ============================================================

    protected function getStats(): array
    {
        $base = $this->baseQuery();

        $income = (clone $base)
            ->where('transaction_type', 'income')
            ->sum(\DB::raw('amount * quantity'));

        $expense = (clone $base)
            ->where('transaction_type', 'expense')
            ->sum(\DB::raw('amount * quantity'));

        $saldo = $income - $expense;

        return [
            Stat::make('Total Pemasukan', 'Rp ' . number_format($income))
                ->color('success'),

            Stat::make('Total Pengeluaran', 'Rp ' . number_format($expense))
                ->color('danger'),

            Stat::make('Saldo', 'Rp ' . number_format($saldo))
                ->color($saldo >= 0 ? 'success' : 'danger'),
        ];
    }
}
