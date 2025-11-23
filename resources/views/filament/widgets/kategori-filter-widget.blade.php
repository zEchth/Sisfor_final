<x-filament::input.select wire:model="category">
    <option value="">Semua Kategori</option>
    @foreach(\App\Models\TransactionCategory::orderBy('name')->get() as $cat)
        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
    @endforeach
</x-filament::input.select>
