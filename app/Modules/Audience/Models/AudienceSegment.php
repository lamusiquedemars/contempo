<?php

namespace App\Modules\Audience\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AudienceSegment extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(AudienceContact::class, 'audience_contact_segment');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SegmentMessage::class);
    }
}
