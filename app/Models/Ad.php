<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ad extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'business_type_id',
        'radio_station_id',
        'service_id',
        'scheduled_at',
        'ad_copy',
        'audio_file'
    ];

    protected $casts = [
        'scheduled_at' => 'date',
    ];

    public function user(): BelongsTo
    {
       return $this->belongsTo(User::class);
    }

    public function businessType(): BelongsTo
    {
        return $this->belongsTo(BusinessType::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function radioStation(): BelongsTo
    {
        return $this->belongsTo(RadioStation::class);
    }

    public function timeSlots(): HasMany
    {
        return $this->hasMany(TimeSlot::class);
    }
}
