<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportConversation extends Model
{
    protected $fillable = [
        'subject',
        'status',
        'customer_email',
        'customer_name',
        'last_message_at',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'conversation_id');
    }
}

