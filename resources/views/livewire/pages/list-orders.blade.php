<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Ad Orders') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex items-center justify-center gap-6">
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Radio Station
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Service
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Total
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Creation Date
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $ad)
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
                           @money($ad->timeSlots->count() * $ad->service->price->getAmount())
                        </td>
                        <td class="px-6 py-4">
                            {{ $ad->created_at->format('M jS, Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
