<?php

namespace App\Modules\CremonaBridge\Models;

use Illuminate\Database\Eloquent\Model;

class CremonaDelivery extends Model
{
    protected $fillable = ['idempotency_key', 'payload', 'status', 'attempts', 'response_status', 'remote_request_id', 'last_error', 'last_attempt_at', 'delivered_at'];

    protected $hidden = ['payload'];

    protected function casts(): array
    {
        return ['payload' => 'encrypted:array', 'last_attempt_at' => 'immutable_datetime', 'delivered_at' => 'immutable_datetime'];
    }
}
