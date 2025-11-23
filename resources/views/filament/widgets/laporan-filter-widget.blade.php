<div class="grid grid-cols-1 md:grid-cols-4 gap-4 w-full mb-6">

    {{-- Periode --}}
    <x-filament::input.select wire:model.defer="periode">
        <option value="harian">Harian</option>
        <option value="bulanan">Bulanan</option>
        <option value="tahunan">Tahunan</option>
        <option value="custom">Custom Range</option>
    </x-filament::input.select>

    {{-- Tipe --}}
    <x-filament::input.select wire:model.defer="selectedType">
        <option value="">Semua Tipe</option>
        <option value="income">Pemasukan</option>
        <option value="expense">Pengeluaran</option>
    </x-filament::input.select>

    {{-- Kategori --}}
    <x-filament::input.select wire:model.defer="selectedCategory">
        <option value="">Semua Kategori</option>
        @foreach (\App\Models\TransactionCategory::orderBy('name')->get() as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
    </x-filament::input.select>

    {{-- Tombol Tampilkan --}}
    <x-filament::button
        color="primary"
        wire:click="$dispatch('refreshFilters')"
    >
        Tampilkan
    </x-filament::button>

    {{-- Custom Range --}}
    @if ($periode === 'custom')
        <input type="date" wire:model.defer="start_date" class="input col-span-2"/>
        <input type="date" wire:model.defer="end_date" class="input col-span-2"/>
    @endif
</div>
