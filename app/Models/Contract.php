<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'artist_id', 'contract_type', 'start_date', 'end_date', 'terms',
        'advance_amount', 'royalty_percentage', 'minimum_albums', 'status',
        'contract_file_path'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'advance_amount' => 'decimal:2',
        'royalty_percentage' => 'decimal:2',
    ];

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public function isActive()
    {
        return $this->status === 'active' &&
               $this->start_date <= now() &&
               ($this->end_date === null || $this->end_date >= now());
    }
}
