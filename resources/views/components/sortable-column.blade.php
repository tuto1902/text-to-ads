@props(['column', 'sortColumn', 'sortAscending'])
<button class="uppercase flex items-center gap-2 group" wire:click="sortBy('{{ $column }}')">
    {{ $slot }}
    @if($sortColumn == $column)
    <x-dynamic-component component="{{ $sortAscending ? 'heroicon-m-chevron-down' : 'heroicon-m-chevron-up' }}" class="size-4" />
    @else
    <x-heroicon-m-chevron-down class="size-4 opacity-0 group-hover:opacity-100" />
    @endif
</button>
