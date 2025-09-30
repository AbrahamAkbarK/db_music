<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StreamingStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'streamable_id', 'streamable_type', 'platform', 'play_count',
        'unique_listeners', 'revenue_generated', 'territory', 'stats_date'
    ];

    protected $casts = [
        'revenue_generated' => 'decimal:4',
        'stats_date' => 'date',
    ];

    public function streamable(): MorphTo
    {
        return $this->morphTo();
    }
}
