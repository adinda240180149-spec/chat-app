<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'chat_id',
        'user_id',
        'content',
    ];

    // Relasi ke Chat: Message milik sebuah Chat
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    // Relasi ke User: Message dikirim oleh seorang User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
