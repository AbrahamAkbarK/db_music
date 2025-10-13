<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Link extends Model
{
    use HasFactory;

    protected $fillable = ['song_id', 'spotify_url', 'apple_music_url', 'youtube_url', 'instagram_url', 'tiktok_url', 'langit_musik_url', 'link_fire_url', 'trebel_url', 'youtube_musik_url'];

    public function song()
    {
        return $this->belongsTo(Song::class);
    }
}
