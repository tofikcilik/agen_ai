<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeterReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'recorded_by',
        'reading_month',
        'previous_value',
        'current_value',
        'usage_m3',
        'notes',
    ];

    protected $casts = [
        'reading_month' => 'date:Y-m',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
