<?php

namespace App\Livewire\Pages;

use Illuminate\Support\Facades\Auth;
use Laravel\Paddle\Checkout;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Livewire\Forms\AdForm;
use App\Models\BusinessType;
use App\Models\RadioStation;
use App\Models\Service;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

#[Layout('layouts.app')]
class CreateAd extends Component
{
    // TO-DO: remove this property
    public int $quantity = 0;

    public bool $isPlaying = false;

    public $businessTypes;
    public $radioStations;
    public $radioStationServices = [];
    public $dates;
    public $timeSlots;
    public ?Service $service = null;

    public AdForm $form;

    public function mount()
    {
        $this->businessTypes = BusinessType::select('id', 'description')->get();
        $this->radioStations = RadioStation::select('id', 'name')->get();
        $this->dates = collect();
        $this->timeSlots = collect();
    }

    public function updatedFormRadioStationId()
    {
        $services = RadioStation::find($this->form->radio_station_id)->services;
        $options = [];
        foreach ($services as $service) {
            $options[$service->id] = $service->description . ' (30s) ' . $service->price;
        }
        $this->radioStationServices = $options;
    }

    public function updatedFormServiceId()
    {
        if ($this->form->radio_station_id){
            $datesPeriod = CarbonPeriod::create(
                Carbon::now()->startOfDay(),
                "1 day",
                Carbon::now()
                    ->addDay(4)
                    ->endOfDay()
            );
            $schedules = RadioStation::find($this->form->radio_station_id)->schedules;
            foreach($datesPeriod as $date) {
                $disabled = false;

                $schedule = $schedules
                    ->where("starts_at", "<=", $date)
                    ->where("ends_at", ">=", $date)
                    ->first();

                // When array_filter returns an empty array, it means the date in question
                // does not have any available schedules. Therefore it should be marked as
                // disabled
                $disabled = [] == array_filter([
                    $schedule->{strtolower($date->format("l")) . "_starts_at"},
                    $schedule->{strtolower($date->format("l")) . "_ends_at"}
                ]);

                $this->dates[$date->format('Y-m-d')] = [
                    'label' => $date->format('m/d/Y'),
                    'disabled' => $disabled
                ];
            }
        }
    }

    public function updatedFormScheduledAt()
    {
        if ($this->form->radio_station_id && $this->form->service_id && $this->form->scheduled_at) {
            $date = Carbon::parse($this->form->scheduled_at);
            $radioStation = RadioStation::find($this->form->radio_station_id);

            $schedule = $radioStation->schedules
                ->where("starts_at", "<=", $date)
                ->where("ends_at", ">=", $date)
                ->first();

            $this->service = Service::find($this->form->service_id);
            $shift = array_filter([
                    $schedule->{strtolower($date->format("l")) . "_starts_at"},
                    $schedule->{strtolower($date->format("l")) . "_ends_at"}
            ]);
            if ($shift) {
                [$startsAt, $endsAt] = $shift;
                $startTime = Carbon::parse($startsAt);
                $endTime = Carbon::parse($endsAt);

                $times = CarbonPeriod::create(
                    $startTime,
                    $this->service->interval_in_minutes . " minutes",
                    $endTime
                );

                foreach ($times as $time) {
                    $formattedTime = $time->format("h:i A");
                    $rawTime = $time->format("H:i:s");

                    $this->timeSlots[$rawTime] = $formattedTime;
                }
            }
        }
    }

    public function updateCheckoutQuantity()
    {
        $this->quantity = collect($this->form->selected_time_slots)->count();
    }

    public function preview()
    {
        // if ($this->form->audioFile) {
        //     $fileName = $this->form->audioFile;
        // } else {
        //     $fileName = Str::uuid()->toString() . '.mp3';
        //     $this->form->audioFile = $fileName;
        // }
        // $response = Http::sink(public_path('/storage/'. $fileName))->withToken(config('services.openai.secret'))
        //     ->post('https://api.openai.com/v1/audio/speech', [
        //         'model' => 'tts-1',
        //         'input' => $this->form->adCopy,
        //         'voice' => 'alloy'
        //     ]);
        // if ($response->failed()) {
        //     throw new Exception('Ooops! Something went wrong.');
        // }

        $this->form->audio_file = 'speech.mp3';

        if ($this->isPlaying) {
            $this->isPlaying = false;
            $this->dispatch('stop-audio');
            return;
        }
        sleep(3);
        $this->isPlaying = true;
        $this->dispatch('play-audio', fileName: $this->form->audio_file);
    }

    #[On('audio-ended')]
    public function onAudioEnded()
    {
       $this->isPlaying = false;
    }

    public function store()
    {
        $this->form->store();
    }

    public function render()
    {
        $checkout = Auth::user()->checkout(['pri_01jas35te8naek9r2zswkh46zp' => $this->quantity])
            ->returnTo(route('dashboard'));

        return view('livewire.pages.create-ad', ['checkout' => $checkout]);
    }
}
