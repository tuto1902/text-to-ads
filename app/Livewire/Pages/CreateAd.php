<?php

namespace App\Livewire\Pages;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Livewire\Forms\AdForm;
use App\Models\BusinessType;
use App\Models\RadioStation;
use App\Models\Service;
use App\Traits\UpdatesRadioScheduleList;
use App\Traits\UpdatesServicesList;
use App\Traits\UpdatesTimeSlotList;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

#[Layout('layouts.app')]
class CreateAd extends Component
{
    use UpdatesServicesList, UpdatesRadioScheduleList, UpdatesTimeSlotList;

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

    public function updateCheckoutQuantity()
    {
        $this->quantity = collect($this->form->selected_time_slots)->count();
    }

    public function preview()
    {
        if ($this->form->audio_file) {
            $fileName = $this->form->audio_file;
        } else {
            $fileName = Str::uuid()->toString() . '.mp3';
            $this->form->audio_file = $fileName;
        }

        $this->textToSpeech($this->form->ad_copy, $fileName);
        // $this->form->audio_file = 'speech.mp3';

        if ($this->isPlaying) {
            $this->isPlaying = false;
            $this->dispatch('stop-audio');
            return;
        }
        // sleep(3);
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
        if (!$this->form->audio_file) {
            $this->form->audio_file = Str::uuid()->toString() . '.mp3';
        }

        $ad = $this->form->store();

        $this->textToSpeech($this->form->ad_copy, $this->form->audio_file);

        if ($ad) {
            $this->redirectRoute('ads.checkout', [ 'ad' => $ad->id ]);
        } else {
            throw new Exception('Failed to create Ad');
        }
    }

    public function render()
    {
        $checkout = Auth::user()->checkout(['pri_01jas35te8naek9r2zswkh46zp' => $this->quantity])
            ->returnTo(route('dashboard'));

        return view('livewire.pages.create-ad', ['checkout' => $checkout]);
    }

    public function textToSpeech($message, $fileName)
    {
        $response = Http::sink(public_path('/storage/'. $fileName))->withToken(config('services.openai.secret'))
            ->post('https://api.openai.com/v1/audio/speech', [
                'model' => 'tts-1',
                'input' => $message,
                'voice' => 'alloy'
            ]);
        if ($response->failed()) {
            throw new Exception('Ooops! Something went wrong.');
        }
    }
}
