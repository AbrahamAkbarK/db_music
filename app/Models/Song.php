<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'album_id',
        'track_number',
        'duration_seconds',
        'genre',
        'lyrics',
        'isrc_code',
        'audio_file_path',
        'demo_file_path',
        'price',
        'status',
        'composer',
        'lyricist',
        'arranger',
        'is_explicit',
        'artist_id',
        'royalty_contract',
        'label',
        'composer_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_explicit' => 'boolean',
    ];

    // Relationships
    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    // One-to-Many: Direct composer (optional)
    public function composer()
    {
        return $this->belongsTo(Composer::class);
    }
    // Many-to-Many: Additional composers via pivot (unchanged)
    // public function composers()
    // {
    //     return $this->belongsToMany(Composer::class);
    //                 // ->withTimestamps()
    //                 // ->withPivot('role');
    // }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
        // ->withPivot('role')
        // ->withTimestamps();
    }

    public function link()
    {
        return $this->hasOne(Link::class);
    }

    public function contracts(): MorphMany
    {
        return $this->morphMany(Contract::class, 'contractable');
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function playlists(): BelongsToMany
    {
        return $this->belongsToMany(Playlist::class)
            ->withPivot('position')
            ->withTimestamps();
    }

    // public function sales(): MorphMany
    // {
    //     return $this->morphMany(Sale::class, 'sellable');
    // }

    // public function streamingStats(): MorphMany
    // {
    //     return $this->morphMany(StreamingStat::class, 'streamable');
    // }

    // Helper methods
    public function getDurationFormatted()
    {
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        return sprintf('%d:%02d', $minutes, $seconds);
    }

    // public function getMainArtists()
    // {
    //     return $this->artists()->wherePivot('role', 'main_artist');
    // }

    // public function getFeaturedArtists()
    // {
    //     return $this->artists()->wherePivot('role', 'featured_artist');
    // }
}
