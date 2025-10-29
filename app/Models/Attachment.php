<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory;
    protected $fillable = ['original_filename', 'storage_path', 'file_type', 'file_size'];

    
    public function attachable()
    {
        return $this->morphTo();
    }
}
