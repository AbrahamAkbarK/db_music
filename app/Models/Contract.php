<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Psy\CodeCleaner\ReturnTypePass;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_number',
        'contract_type',
        'amount',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the parent contractable model (e.g., a Song).
     */
    public function contractable(): MorphTo
    {
        return $this->morphTo();
    }


    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('status', 'active')
                     ->whereNotNull('end_date')
                     ->where('end_date', '<', now());
    }
}
