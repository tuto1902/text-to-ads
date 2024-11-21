<?php

namespace App\Traits;

use App\Models\RadioStation;

trait UpdatesServicesList
{
    public function updatedFormRadioStationId()
    {
        $services = RadioStation::find($this->form->radio_station_id)->services;
        $options = [];
        foreach ($services as $service) {
            $options[$service->id] = $service->description . ' (30s) ' . $service->price;
        }
        $this->radioStationServices = $options;
    }
}
