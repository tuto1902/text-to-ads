<?php

namespace App\Traits;

use App\Models\RadioStation;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;

trait UpdatesRadioScheduleList
{
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
}
