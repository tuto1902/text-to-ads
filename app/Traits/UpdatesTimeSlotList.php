<?php

namespace App\Traits;

use App\Models\RadioStation;
use App\Models\Service;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;

trait UpdatesTimeSlotList
{
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
}
