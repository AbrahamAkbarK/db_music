<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Composer extends Model
{
    /** @use HasFactory<\Database\Factories\ComposerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'nationality',
        'birth_date',
        'death_date',
        'email',
        'phone',
        'address',
        'bio',
        'image_url',
    ];
    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
        'email' => 'string',
    ];
    // Relationship: Many-to-many with Songs
    public function songs()
    {
        return $this->hasMany(Song::class, 'composer_id');  // Specify table name explicitly
                    // ->withTimestamps()
                    // ->withPivot('role');  // Include role in queries
    }
    // public function songs()
    // {
    //     return $this->belongsToMany(Song::class);
    //                 // ->withTimestamps()
    //                 // ->withPivot('role')  // Include pivot fields (e.g., 'primary_composer')
    //                 // ->select('songs.id', 'songs.title')  // Prefix to avoid ambiguity (from prior fix)
    //                 // ->orderBy('songs.title', 'asc');
    // }

    public function artist()
    {
        // The final model we want: Artist
        // The intermediate model: Song
        return $this->hasManyThrough(Artist::class, Song::class);
    }


    // Scope: For easy querying
    public function scopeByNationality($query, $nationality)
    {
        return $query->where('nationality', $nationality);
    }

     public function getSongsCountAttribute()
    {
        return $this->songs()->count();
    }
}
