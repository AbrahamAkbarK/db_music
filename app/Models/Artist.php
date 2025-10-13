<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'stage_name', 'biography', 'genre', 'country', 'birth_date',
        'email', 'phone', 'website', 'category','manager', 'spotify_url', 'apple_music_url',
        'youtube_url', 'instagram_url', 'facebook_url', 'twitter_url',
        'profile_image', 'status', 'contract_start_date', 'contract_end_date'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
    ];

    // Relationships
    public function albums(): BelongsToMany
    {
        return $this->belongsToMany(Album::class, 'artist_album', 'artist_id', 'album_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function songs()
    {
        return $this->hasMany(Song::class);
                    // ->withPivot('role')
                    // ->withTimestamps();
    }

    public function composers()
    {
        // The final model we want: Composer
        // The intermediate model: Song
        return $this->hasManyThrough(Composer::class, Song::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function contracts(): MorphMany
    {
        return $this->morphMany(Contract::class, 'contractable');
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
    public function getMainAlbums()
    {
        return $this->albums()->wherePivot('role', 'main_artist');
    }

    public function getFeaturedAlbums()
    {
        return $this->albums()->wherePivot('role', 'featured_artist');
    }

    public function getActiveContract()
    {
        return $this->contracts()->where('status', 'active')->first();
    }
}
