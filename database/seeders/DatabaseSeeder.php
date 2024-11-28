<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\BusinessType;
use App\Models\RadioStation;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Arturo',
            'email' => 'arturo@example.com',
        ]);

        $businessType = BusinessType::create([
            'description' => 'Service'
        ]);

        $radioStation = RadioStation::create([
            'name' => 'Radio Station A'
        ]);

        $service = Service::create([
            'radio_station_id' => $radioStation->id,
            'price' => 1200,
            'interval_in_minutes' => 30,
            'description' => 'Text To Speech Ad'
        ]);

        $schedule = Schedule::create([
            'radio_station_id' => $radioStation->id,
            'starts_at' => '2024-10-01',
            'ends_at' => '2024-12-31',
            'monday_starts_at' => '11:00',
            'monday_ends_at' => '14:00',
            'tuesday_starts_at' => '11:00',
            'tuesday_ends_at' => '14:00',
            'wednesday_starts_at' => '11:00',
            'wednesday_ends_at' => '14:00',
        ]);

        $ad = Ad::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'radio_station_id' => $radioStation->id,
            'business_type_id' => $businessType->id,
            'ad_copy' => 'test ad copy',
            'audio_file' => 'speech.mp3',
            'scheduled_at' => Carbon::now()->format('Y-m-d'),
            'status' => 'paid'
        ]);

        TimeSlot::create([
            'ad_id' => $ad->id,
            'time' => Carbon::parse('12:00:00')
        ]);
    }
}
