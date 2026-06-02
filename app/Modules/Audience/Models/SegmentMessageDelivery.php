<?php

namespace App\Modules\Audience\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SegmentMessageDelivery extends Model
{
    protected $fillable = [
        'segment_message_id',
        'audience_contact_id',
        'email',
        'status',
        'error_message',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function segmentMessage(): BelongsTo
    {
        return $this->belongsTo(SegmentMessage::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(AudienceContact::class, 'audience_contact_id');
    }
}
