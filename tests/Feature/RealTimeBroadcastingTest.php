<?php

use App\Models\User;
use App\Models\Chat;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('event MessageSent berhasil dipancarkan saat pesan dikirim', function () {
    Event::fake();

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

    // Buat chat room
    $chat = Chat::create(['type' => 'private']);
    $chat->users()->attach([$budi->id, $andi->id]);

    // Budi mengirim pesan via HTTP
    $this->actingAs($budi)->post(route('messages.send', $chat->id), [
        'content' => 'Halo Andi!',
    ]);

    // Memastikan event MessageSent berhasil dipicu
    Event::assertDispatched(MessageSent::class, function ($event) use ($chat, $budi) {
        return $event->message->content === 'Halo Andi!' &&
               $event->message->chat_id === $chat->id &&
               $event->message->user_id === $budi->id;
    });
});

test('event MessageSent memiliki konfigurasi channel dan payload broadcast yang benar', function () {
    $budi = User::create([
        'name' => 'Budi Santoso',
        'username' => 'budi',
        'password' => bcrypt('password123'),
    ]);

    $chat = Chat::create(['type' => 'private']);
    $chat->users()->attach([$budi->id]);

    $message = \App\Models\Message::create([
        'chat_id' => $chat->id,
        'user_id' => $budi->id,
        'content' => 'Pesan uji coba',
    ]);
    
    $message->load('user');

    $event = new MessageSent($message);

    // Memastikan mengarah ke private channel 'chat.{chat_id}'
    $channels = $event->broadcastOn();
    expect($channels)->toHaveCount(1);
    expect($channels[0]->name)->toBe('private-chat.' . $chat->id);

    // Memastikan format data payload broadcast lengkap
    $payload = $event->broadcastWith();
    expect($payload)->toHaveKeys(['id', 'chat_id', 'user_id', 'sender_name', 'content', 'time']);
    expect($payload['sender_name'])->toBe('Budi Santoso');
    expect($payload['content'])->toBe('Pesan uji coba');
});
