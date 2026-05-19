<?php

use App\Models\User;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('anggota chat room dapat mengirimkan pesan via HTTP', function () {
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

    // Budi mengirim pesan
    $response = $this->actingAs($budi)->post(route('messages.send', $chat->id), [
        'content' => 'Halo Andi! Apa kabar?',
    ]);

    // Memastikan pesan tersimpan di database
    $message = Message::first();
    expect($message)->not->toBeNull();
    expect($message->content)->toBe('Halo Andi! Apa kabar?');
    expect($message->user_id)->toBe($budi->id);

    // Memastikan redirect kembali ke chat room
    $response->assertRedirect(route('chats.view', $chat->id));
});

test('tamu tidak dapat mengirimkan pesan dan dialihkan ke login', function () {
    $chat = Chat::create(['type' => 'private']);

    $response = $this->post(route('messages.send', $chat->id), [
        'content' => 'Pesan tanpa login',
    ]);

    $response->assertRedirect(route('login'));
    expect(Message::count())->toBe(0);
});

test('bukan anggota chat room tidak dapat mengirimkan pesan', function () {
    $budi = User::create(['name' => 'Budi', 'username' => 'budi', 'password' => bcrypt('password123')]);
    $andi = User::create(['name' => 'Andi', 'username' => 'andi', 'password' => bcrypt('password123')]);
    $cindy = User::create(['name' => 'Cindy', 'username' => 'cindy', 'password' => bcrypt('password123')]);

    // Chat room budi dan andi
    $chat = Chat::create(['type' => 'private']);
    $chat->users()->attach([$budi->id, $andi->id]);

    // Cindy mencoba menyusupkan pesan ke chat budi dan andi
    $response = $this->actingAs($cindy)->post(route('messages.send', $chat->id), [
        'content' => 'Cindy menyusup',
    ]);

    $response->assertStatus(403);
    expect(Message::count())->toBe(0);
});

test('pengiriman pesan kosong akan memicu error validasi', function () {
    $budi = User::create(['name' => 'Budi', 'username' => 'budi', 'password' => bcrypt('password123')]);
    $andi = User::create(['name' => 'Andi', 'username' => 'andi', 'password' => bcrypt('password123')]);

    $chat = Chat::create(['type' => 'private']);
    $chat->users()->attach([$budi->id, $andi->id]);

    // Budi mengirim pesan kosong
    $response = $this->actingAs($budi)->post(route('messages.send', $chat->id), [
        'content' => '',
    ]);

    $response->assertSessionHasErrors(['content']);
    expect(Message::count())->toBe(0);
});
