<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Ad Orders') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-end">
            <x-text-input wire:model.live.debounce="search" placeholder="Search" />
        </div>
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            <x-sortable-column column="station" :$sortColumn :$sortAscending>
                                Radio Station
                            </x-sortable-column>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <x-sortable-column column="status" :$sortColumn :$sortAscending>
                                Status
                            </x-sortable-column>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <x-sortable-column column="service" :$sortColumn :$sortAscending>
                                Service
                            </x-sortable-column>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <x-sortable-column column="total" :$sortColumn :$sortAscending>
                                Total
                            </x-sortable-column>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <x-sortable-column column="creation_date" :$sortColumn :$sortAscending>
                                Creation Date
                            </x-sortable-column>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Checkout
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ads as $ad)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $ad->radioStation->name }}
                        </th>
                        <td class="px-6 py-4">
                            <span
                                @class([
                                    'text-xs font-medium me-2 px-2 py-1 rounded inline-flex items-center gap-2',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' => $ad->status->color() == 'green',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' => $ad->status->color() == 'yellow',
                                ])
                            >
                                {{ $ad->status->label() }}
                                <x-dynamic-component class="size-4" component="heroicon-m-{{ $ad->status->icon() }}" />
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $ad->service->description }}
                        </td>
                        <td class="px-6 py-4">
                           @money($ad->time_slots_count * $ad->service->price->getAmount())
                        </td>
                        <td class="px-6 py-4">
                            {{ $ad->created_at->format('M jS, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($ad->status == App\Enums\OrderStatus::Pending)
                            <a href="{{ route('ads.checkout', ['ad' => $ad->id]) }}">Checkout</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $ads->links() }}
    </div>
</div>
