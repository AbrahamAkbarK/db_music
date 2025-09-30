<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sellable_id', 'sellable_type', 'platform', 'quantity_sold',
        'unit_price', 'total_amount', 'artist_royalty', 'label_commission',
        'sale_date', 'territory'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'artist_royalty' => 'decimal:2',
        'label_commission' => 'decimal:2',
        'sale_date' => 'date',
    ];

    public function sellable(): MorphTo
    {
        return $this->morphTo();
    }
}
