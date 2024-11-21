<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Checkout') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between gap-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex-1">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                {{ __("Order Summary") }}
                <div class="flex flex-col mt-6 gap-4">
                    @foreach($ad->timeSlots as $timeSlot)
                    <div class="p-4 border border-100 dark:border-gray-700 rounded-lg flex flex-col gap-2">
                        <div>
                            <span class="font-bold">{{ $ad->service->description }}</span> - {{ $ad->radioStation->name }}, {{ $ad->scheduled_at->format('D dS') }} {{ $timeSlot->time->format('H:i A') }}
                        </div>
                        <span class="text-sm">{{ $ad->service->price }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex-1 max-w-md order-first sm:order-last">
            <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                {{ __("Review And Checkout") }}
                <div class="mt-6 text-2xl font-extrabold">
                    <span>Total: </span> @money($ad->timeSlots->count() * $ad->service->price->getAmount())
                </div>
                <x-paddle-button :checkout="$checkout" class="flex items-center w-full justify-center mt-6 px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Checkout
                </x-paddle-button>
                <!-- <x-primary-button class="w-full flex items-center justify-center" :disabled="false">Checkout</x-primary-button> -->
            </div>
        </div>
    </div>
</div>
