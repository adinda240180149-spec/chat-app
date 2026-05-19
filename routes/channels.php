<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id}', function ($user, $id) {
    // Ambil data chat berdasarkan ID
    $chat = \App\Models\Chat::find($id);

    if (!$chat) {
        return false;
    }

    // Pastikan user terotentikasi adalah salah satu peserta chat room ini
    return $chat->users->contains($user->id);
});
