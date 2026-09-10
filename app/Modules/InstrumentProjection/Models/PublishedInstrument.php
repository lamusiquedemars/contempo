<?php

namespace App\Modules\InstrumentProjection\Models;

use Illuminate\Database\Eloquent\Model;

class PublishedInstrument extends Model
{
    protected $fillable = ['cremona_id', 'reference', 'name', 'family', 'maker', 'description', 'sale_amount', 'rental_amount', 'availability', 'published_at'];

    protected function casts(): array
    {
        return ['sale_amount' => 'decimal:2', 'rental_amount' => 'decimal:2', 'published_at' => 'immutable_datetime'];
    }
}
