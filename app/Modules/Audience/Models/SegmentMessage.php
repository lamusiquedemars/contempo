<?php

namespace App\Modules\Audience\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SegmentMessage extends Model
{
    protected $fillable = [
        'audience_segment_id',
        'subject',
        'body',
        'status',
        'recipients_count',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function segment(): BelongsTo
    {
        return $this->belongsTo(AudienceSegment::class, 'audience_segment_id');
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(SegmentMessageDelivery::class);
    }
}
