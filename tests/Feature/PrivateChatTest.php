<?php

use App\Models\User;
use App\Models\Chat;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user dapat mencari username dan memulai private chat baru', function () {
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

    // Budi mencari Andi
    $response = $this->actingAs($budi)->post('/chats/private', [
        'username' => 'andi',
    ]);

    // Memastikan chat room bertipe private terbuat di database
    $chat = Chat::where('type', 'private')->first();
    expect($chat)->not->toBeNull();

    // Memastikan budi dan andi terhubung di pivot
    expect($chat->users)->toHaveCount(2);
    expect($chat->users->pluck('username'))->toContain('budi', 'andi');

    // Memastikan redirect ke halaman detail chat
    $response->assertRedirect(route('chats.view', $chat->id));
});

test('mencari username yang sudah memiliki private chat akan membuka chat yang sama tanpa membuat baru', function () {
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

    // Buat chat private secara manual terlebih dahulu
    $chat = Chat::create(['type' => 'private']);
    $chat->users()->attach([$budi->id, $andi->id]);

    // Budi mencari Andi lagi
    $response = $this->actingAs($budi)->post('/chats/private', [
        'username' => 'andi',
    ]);

    // Memastikan jumlah total chat di database tetap 1 (tidak membuat baru)
    expect(Chat::count())->toBe(1);
    $response->assertRedirect(route('chats.view', $chat->id));
});

test('user tidak dapat memulai chat dengan diri sendiri', function () {
    $budi = User::create([
        'name' => 'Budi Santoso',
        'username' => 'budi',
        'password' => bcrypt('password123'),
    ]);

    // Budi mencari budi
    $response = $this->actingAs($budi)->post('/chats/private', [
        'username' => 'budi',
    ]);

    $response->assertSessionHasErrors(['username']);
    expect(Chat::count())->toBe(0);
});

test('user tidak dapat memulai chat dengan username yang tidak terdaftar', function () {
    $budi = User::create([
        'name' => 'Budi Santoso',
        'username' => 'budi',
        'password' => bcrypt('password123'),
    ]);

    // Budi mencari username fiktif
    $response = $this->actingAs($budi)->post('/chats/private', [
        'username' => 'unknownuser',
    ]);

    $response->assertSessionHasErrors(['username']);
    expect(Chat::count())->toBe(0);
});

test('anggota chat dapat melihat chat room sedangkan non-anggota dilarang masuk', function () {
    $budi = User::create(['name' => 'Budi', 'username' => 'budi', 'password' => bcrypt('password123')]);
    $andi = User::create(['name' => 'Andi', 'username' => 'andi', 'password' => bcrypt('password123')]);
    $cindy = User::create(['name' => 'Cindy', 'username' => 'cindy', 'password' => bcrypt('password123')]);

    // Chat room budi dan andi
    $chat = Chat::create(['type' => 'private']);
    $chat->users()->attach([$budi->id, $andi->id]);

    // Budi (anggota) dapat mengakses chat room
    $responseBudi = $this->actingAs($budi)->get(route('chats.view', $chat->id));
    $responseBudi->assertStatus(200);

    // Cindy (bukan anggota) dilarang masuk (403 Forbidden)
    $responseCindy = $this->actingAs($cindy)->get(route('chats.view', $chat->id));
    $responseCindy->assertStatus(403);
});
