<?php

namespace App\Models;

use Cknow\Money\Casts\MoneyIntegerCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'price' => MoneyIntegerCast::class,
    ];

    public function radioStation(): BelongsTo
    {
        return $this->belongsTo(RadioStation::class);
    }
}
