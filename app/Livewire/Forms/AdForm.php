<?php

namespace App\Livewire\Forms;

use App\Models\Ad;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
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

    public function rules(): array
    {
        return [
            'audio_file' => 'required',
            'ad_copy' => 'required',
            'business_type_id' =>[ 'required' ],
            'radio_station_id' =>[ 'required' ],
            'service_id' =>[ 'required' ],
            'scheduled_at' => 'required',
            'selected_time_slots' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'business_type_id.required' => 'The business type field is required',
            'service_id.required' => 'The service field is required',
            'scheduled_at.required' => 'The date field is required',
            'radio_station_id.required' => 'The radio station field is required',
            'selected_time_slots.required' => 'You must select at least one time slot'
        ];
    }

    public function store()
    {
        Auth::user()->ads()->create([
            $this->validate()
        ]);
    }

}
