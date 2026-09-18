<?php

namespace App\Modules\InstrumentProjection\Models;

use Illuminate\Database\Eloquent\Model;

class PublishedInstrument extends Model
{
    protected $fillable = ['cremona_id', 'reference', 'slug', 'name', 'family', 'maker', 'description', 'price_label', 'attributes', 'media', 'sale_amount', 'rental_amount', 'availability', 'published_at'];

    protected function casts(): array
    {
        return ['sale_amount' => 'decimal:2', 'rental_amount' => 'decimal:2', 'published_at' => 'immutable_datetime', 'attributes' => 'array', 'media' => 'array'];
    }

    public function displayPrice(): string
    {
        $label = trim((string) $this->price_label);

        if ($label !== '') {
            return preg_match('/^\\d[\\d\\s.,]*$/u', $label) ? $label.' €' : $label;
        }

        if ($this->sale_amount !== null) {
            return number_format((float) $this->sale_amount, 0, ',', "\u{202f}").' €';
        }

        return 'Sur demande';
    }
}
