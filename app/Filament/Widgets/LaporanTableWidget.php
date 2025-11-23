<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;

class LaporanTableWidget extends TableWidget
{
    protected static ?string $heading = 'Daftar Transaksi';
    protected int|string|array $columnSpan = 12;

    public ?string $periode = 'bulanan';
    public ?string $selectedCategory = null;
    public ?string $selectedType = null;
    public ?string $start_date = null;
    public ?string $end_date = null;

    # =====================================================
    # LISTENERS DARI WIDGET TUNGGAL
    # =====================================================

    #[On('periodeUpdated')]
    public function setPeriode($periode)
    {
        $this->periode = $periode;
        $this->resetTable();
    }

    #[On('categoryUpdated')]
    public function setCategory($category)
    {
        $this->selectedCategory = $category;
        $this->resetTable();
    }

    #[On('typeUpdated')]
    public function setType($type)
    {
        $this->selectedType = $type;
        $this->resetTable();
    }

    #[On('dateUpdated')]
    public function setDateRange($range)
    {
        $this->start_date = $range['start'];
        $this->end_date   = $range['end'];
        $this->resetTable();
    }

    # =====================================================
    # LISTENER DARI WIDGET CAMPUR FILTER
    # =====================================================
    
    #[On('filtersUpdated')]
    public function applyFilters($data)
    {
        $this->periode         = $data['periode'];
        $this->selectedType    = $data['type'];
        $this->selectedCategory = $data['category'];
        $this->start_date      = $data['start_date'];
        $this->end_date        = $data['end_date'];

        $this->resetTable();
    }

    
    # =====================================================
    # EXPORT
    # =====================================================

    public function exportPDF()
    {
        $data = $this->getTableQuery()->get();
        $pdf  = Pdf::loadView('exports.laporan-pdf', compact('data'));

        return response()->streamDownload(
            fn () => print($pdf->stream()),
            'laporan-keuangan.pdf'
        );
    }

    public function exportExcel()
    {
        $file = \App\Exports\LaporanExport::generate(
            $this->start_date,
            $this->end_date
        );

        return response()->download($file)->deleteFileAfterSend(true);
    }

    # =====================================================
    # QUERY TABLE
    # =====================================================

    protected function getTableQuery(): Builder
    {
        $query = Transaction::with('category')->whereNull('deleted_at');

        if ($this->periode === 'harian') {
            $query->whereDate('transaction_date', today());
        } elseif ($this->periode === 'bulanan') {
            $query->whereMonth('transaction_date', now()->month)
                  ->whereYear('transaction_date', now()->year);
        } elseif ($this->periode === 'tahunan') {
            $query->whereYear('transaction_date', now()->year);
        } elseif ($this->periode === 'custom') {
            if ($this->start_date && $this->end_date) {
                $query->whereBetween(
                    'transaction_date',
                    [Carbon::parse($this->start_date), Carbon::parse($this->end_date)]
                );
            }
        }

        if ($this->selectedCategory) {
            $query->where('transaction_category_id', $this->selectedCategory);
        }

        if ($this->selectedType) {
            $query->where('transaction_type', $this->selectedType);
        }

        return $query->orderBy('transaction_date');
    }

    # =====================================================
    # TABLE UI
    # =====================================================

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Action::make('exportPdf')->label('Export PDF')->color('danger')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(fn () => $this->exportPDF()),

                Action::make('exportExcel')->label('Export Excel')->color('success')
                    ->icon('heroicon-o-table-cells')
                    ->action(fn () => $this->exportExcel()),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('transaction_date')->label('Tanggal')->date(),
                Tables\Columns\TextColumn::make('item.name')->label('Item')->searchable(),
                Tables\Columns\TextColumn::make('category.name')->label('Kategori')->searchable(),
                Tables\Columns\BadgeColumn::make('transaction_type')
                    ->label('Tipe')
                    ->colors([
                        'success' => 'income',
                        'danger' => 'expense',
                    ])
                    ->formatStateUsing(fn ($state) => $state === 'income' ? 'Pemasukan' : 'Pengeluaran')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')->label('Harga')->money('idr', true),
                Tables\Columns\TextColumn::make('quantity')->label('Qty'),
                Tables\Columns\TextColumn::make('total_amount')->label('Total')->money('idr', true),
            ])
            ->paginated(false)
            ->striped();
    }
}
