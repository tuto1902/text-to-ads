<?php

namespace App\Livewire\Forms;

use App\Models\Ad;
use App\Models\TimeSlot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $this->validate();
        $ad = null;
        DB::transaction(function () use (&$ad) {
            $ad = Ad::create([
                'ad_copy' => $this->ad_copy,
                'business_type_id' => $this->business_type_id,
                'radio_station_id' => $this->radio_station_id,
                'service_id' => $this->service_id,
                'scheduled_at' => $this->scheduled_at,
                'audio_file' => $this->audio_file,
                'user_id' => Auth::user()->id,
                'status' => 'pending'
            ]);

            $timeSlots = [];
            foreach ($this->selected_time_slots as $time) {
                $timeSlots[] = new TimeSlot([ 'time' => $time ]);
            }

            $ad->timeSlots()->saveMany($timeSlots);

        });

        return $ad;
    }

}
