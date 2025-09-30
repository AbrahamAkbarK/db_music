<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Playlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'cover_image', 'visibility',
        'total_songs', 'total_duration'
    ];

    // Relationships
    public function songs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class)
                    ->withPivot('position', 'added_at')
                    ->orderBy('pivot_position');
    }

    // Helper methods
    public function updateStats()
    {
        $this->total_songs = $this->songs()->count();
        $this->total_duration = $this->songs()->sum('duration_seconds');
        $this->save();
    }
}
