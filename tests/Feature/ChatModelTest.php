<?php

use App\Models\User;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('relasi database chat dan message berfungsi dengan benar', function () {
    // 1. Buat dua user percobaan
    $budi = User::create([
        'name' => 'Budi Santoso',
        'username' => 'budi',
        'password' => bcrypt('password123'),
    ]);

    $andi = User::create([
        'name' => 'Andi Pratama',
        'username' => 'andi',
        'password' => bcrypt('password123'),
    ]);

    // 2. Buat chat room private baru
    $chat = Chat::create([
        'type' => 'private',
    ]);

    // 3. Hubungkan kedua user ke chat room tersebut lewat pivot
    $chat->users()->attach([$budi->id, $andi->id]);

    // 4. Kirim pesan dari budi ke chat tersebut
    $message = Message::create([
        'chat_id' => $chat->id,
        'user_id' => $budi->id,
        'content' => 'Halo Andi! Ini pesan tes.',
    ]);

    // --- UJI RELASI ELOQUENT ---

    // A. Pastikan chat room terhubung dengan kedua user
    expect($chat->users)->toHaveCount(2);
    expect($chat->users->pluck('username'))->toContain('budi', 'andi');

    // B. Pastikan user terhubung ke chat room
    expect($budi->chats)->toHaveCount(1);
    expect($budi->chats->first()->id)->toBe($chat->id);
    expect($andi->chats)->toHaveCount(1);
    expect($andi->chats->first()->id)->toBe($chat->id);

    // C. Pastikan chat memiliki pesan yang dikirim
    expect($chat->messages)->toHaveCount(1);
    expect($chat->messages->first()->content)->toBe('Halo Andi! Ini pesan tes.');

    // D. Pastikan pesan terhubung dengan pengirim (User) dan ruangannya (Chat)
    expect($message->user->id)->toBe($budi->id);
    expect($message->user->name)->toBe('Budi Santoso');
    expect($message->chat->id)->toBe($chat->id);
});
