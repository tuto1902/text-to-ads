<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Create Ad') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-md mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-lg text-gray-800 dark:text-gray-200 font-bold">
                    Ad Information
                </h1>
                <form wire:submit="store" class="mt-6 flex flex-col space-y-4">

                    <x-input-error :messages="$errors->all()" />

                    <div class="space-y-2 w-full max-w-md flex flex-col">
                        <x-input-label value="Ad Copy" required />
                        <x-textarea wire:model="form.ad_copy" {{ $errors->has('ad_copy') ? 'hasError' : '' }} />
                        <div class="self-end flex items-center">
                            <div wire:loading.flex wire:target="preview" class="flex pr-4 items-center">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <x-secondary-button wire:loading.attr="disabled" wire:click="preview" class="self-end flex items-center">
                                <span x-text="$wire.isPlaying ? 'Stop' : 'Play'"></span>
                            </x-secondary-button>
                        </div>
                        @error('ad_copy')
                        @enderror
                    </div>
                    <div class="space-y-2 w-full max-w-md flex flex-col">
                        <x-input-label value="Business Type" required />
                        <x-select-dropdown wire:model="form.business_type_id">
                            <option value="">Choose your business type...</option>
                            @foreach($businessTypes as $businessType)
                            <option value="{{ $businessType->id }}">{{ $businessType->description }}</option>
                            @endforeach
                        </x-select-dropdown>
                    </div>
                    <div class="space-y-2 w-full max-w-md flex flex-col">
                        <x-input-label value="Radio Station" required />
                        <x-select-dropdown wire:model.change="form.radio_station_id">
                            <option value="">Choose a radio station...</option>
                            @foreach($radioStations as $radioStation)
                            <option value="{{ $radioStation->id }}">{{ $radioStation->name }}</option>
                            @endforeach
                        </x-select-dropdown>
                    </div>
                    <template x-if="$wire.form.radio_station_id">
                        <div class="space-y-2 w-full max-w-md flex flex-col">
                            <x-input-label value="Service" required />
                            @foreach($radioStationServices as $service_id => $description)
                            <label class="flex items-center gap-2 font-medium text-sm">
                                <input type="radio" name="service_id" value="{{ $service_id }}" wire:model.change="form.service_id">
                                {{ $description }}
                            </label>
                            @endforeach
                        </div>
                    </template>
                    <template x-if="$wire.form.radio_station_id && $wire.form.service_id">
                        <div class="space-y-2 w-full max-w-md flex flex-col">
                            <x-input-label value="Date" required />
                            <x-select-dropdown wire:model.change="form.scheduled_at">
                                <option value="">Select a date...</option>
                                @foreach($dates as $date => $option)
                                <option value="{{ $date }}" {{ $option['disabled'] ? 'disabled' : '' }}>{{ $option['label'] }}</option>
                                @endforeach
                            </x-select-dropdown>
                        </div>
                    </template>
                    <template x-if="$wire.form.radio_station_id && $wire.form.service_id && $wire.form.scheduled_at">
                        <div class="flex items-start justify-between">
                            <div class="space-y-2 w-full max-w-md flex flex-col">
                                <x-input-label value="Time Slots" required />
                                @foreach($timeSlots as $rawTime => $formattedTime)
                                <label class="flex items-center gap-2 font-medium text-sm">
                                    <input type="checkbox" name="time_slots" value="{{ $rawTime }}" wire:click="updateCheckoutQuantity" wire:model="form.selected_time_slots">
                                    {{ $formattedTime }}
                                </label>
                                @endforeach
                            </div>
                            <div class="space-y-2 w-full flex flex-col">
                                <x-input-label value="Summary" />
                                @foreach($form->selected_time_slots as $timeSlot)
                                <span class="font-medium text-sm">{{ Illuminate\Support\Carbon::parse($timeSlot)->format('H:i A') }} ({{ $service->price }})</span>
                                @endforeach
                                <div class="border border-t border-gray-100 dark:border-gray-700"></div>
                                <div>
                                    <span class="font-bold dark:text-white">Total: </span>@money(collect($form->selected_time_slots)->count() * $service?->price->getAmount())
                                </div>
                            </div>
                        </div>
                    </template>
                    <x-primary-button class="w-full flex items-center justify-center" :disabled="false">Review Order</x-primary-button>
                </form>
                <!-- <x-paddle-button :checkout="$checkout" class="inline-flex items-center mt-6 px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Proceed To Checkout
                </x-paddle-button> -->
            </div>
        </div>
    </div>
</div>
@script
<script>
    let audio = new Audio();

    audio.addEventListener('ended', () => {
        $wire.dispatch('audio-ended');
    });

    $wire.on('play-audio', (event) => {
        audio.pause();
        audio.src = '/storage/'+ event.fileName + '?ts=' + Date.now();
        audio.play();
    });

    $wire.on('stop-audio', () => {
        audio.pause();
    })
</script>
@endscript
