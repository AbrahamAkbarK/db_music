<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $fillable = ['name','phone','email','artist_id'];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
