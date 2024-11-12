<?php

namespace App\Livewire\Forms;

use App\Models\Ad;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AdForm extends Form
{
    public $audio_file = '';
    public $ad_copy = '';
    public $business_type_id = null;
    public $radio_station_id = null;
    public $service_id = null;
    public $scheduled_at = null;
    public $selected_time_slots = [];

    public function store()
    {
        Ad::create([
            'audio_file' => $this->audio_file,
            'ad_copy' => $this->ad_copy,
            'business_type_id' => $this->business_type_id,
            'radio_station_id' => $this->radio_station_id,
            'service_id' => $this->service_id,
            'scheduled_at' => $this->scheduled_at,
            'user_id' => Auth::user()->id
        ]);
    }

}
