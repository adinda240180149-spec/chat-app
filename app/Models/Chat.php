<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    protected $fillable = [
        'name',
        'type',
    ];

    // Relasi ke User: Chat diikuti oleh banyak User (Pivot)
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    // Relasi ke Message: Chat memiliki banyak Message
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
