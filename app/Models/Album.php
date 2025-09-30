<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'genre', 'subgenre', 'release_date',
        'type', 'cover_image', 'upc_code', 'price', 'status', 'total_tracks',
        'duration_seconds', 'producer', 'record_label', 'recording_studio',
        'recording_year'
    ];

    protected $casts = [
        'release_date' => 'date',
        'price' => 'decimal:2',
    ];

    // Relationships
    public function artists(): BelongsToMany
    {
        return $this->belongsToMany(Artist::class, 'artist_album', 'album_id', 'artist_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function songs()
    {
        return $this->hasMany(Song::class);
    }

    public function sales(): MorphMany
    {
        return $this->morphMany(Sale::class, 'sellable');
    }

    public function streamingStats(): MorphMany
    {
        return $this->morphMany(StreamingStat::class, 'streamable');
    }

    // Helper methods
    public function getMainArtists()
    {
        return $this->artists()->wherePivot('role', 'main_artist');
    }

    public function getFeaturedArtists()
    {
        return $this->artists()->wherePivot('role', 'featured_artist');
    }

    public function getTotalDurationFormatted()
    {
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        return sprintf('%d:%02d', $minutes, $seconds);
    }

    public function updateTotalDuration()
    {
        $this->duration_seconds = $this->songs()->sum('duration_seconds');
        $this->total_tracks = $this->songs()->count();
        $this->save();
    }
}
