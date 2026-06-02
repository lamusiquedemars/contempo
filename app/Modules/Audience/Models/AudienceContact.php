<?php

namespace App\Modules\Audience\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AudienceContact extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'notes',
        'accepts_email',
        'unsubscribed_at',
        'last_contacted_at',
    ];

    protected function casts(): array
    {
        return [
            'accepts_email' => 'boolean',
            'unsubscribed_at' => 'datetime',
            'last_contacted_at' => 'datetime',
        ];
    }

    public function segments(): BelongsToMany
    {
        return $this->belongsToMany(AudienceSegment::class, 'audience_contact_segment');
    }

    public function canReceiveSegmentEmail(): bool
    {
        return $this->accepts_email && $this->unsubscribed_at === null;
    }
}
