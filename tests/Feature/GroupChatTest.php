<?php

use App\Models\User;
use App\Models\Chat;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user dapat membuat grup chat baru', function () {
    $budi = User::create([
        'name' => 'Budi Santoso',
        'username' => 'budi',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->actingAs($budi)->post('/chats/group', [
        'group_name' => 'Grup Belajar Laravel',
    ]);

    // Memastikan chat room tipe grup terbuat di database
    $chat = Chat::where('type', 'group')->first();
    expect($chat)->not->toBeNull();
    expect($chat->name)->toBe('Grup Belajar Laravel');

    // Memastikan budi (pembuat) otomatis bergabung di grup tersebut
    expect($chat->users)->toHaveCount(1);
    expect($chat->users->first()->id)->toBe($budi->id);

    // Memastikan redirect ke halaman detail grup chat
    $response->assertRedirect(route('chats.view', $chat->id));
});

test('anggota grup dapat mengundang user lain menggunakan username', function () {
    $budi = User::create(['name' => 'Budi', 'username' => 'budi', 'password' => bcrypt('password123')]);
    $andi = User::create(['name' => 'Andi', 'username' => 'andi', 'password' => bcrypt('password123')]);

    // Budi membuat grup chat
    $chat = Chat::create(['name' => 'Grup Laravel', 'type' => 'group']);
    $chat->users()->attach($budi->id);

    // Budi mengundang Andi
    $response = $this->actingAs($budi)->post(route('chats.invite', $chat->id), [
        'username' => 'andi',
    ]);

    // Memastikan andi berhasil bergabung ke grup
    expect($chat->fresh()->users)->toHaveCount(2);
    expect($chat->fresh()->users->pluck('username'))->toContain('budi', 'andi');

    // Memastikan redirect kembali ke detail grup chat
    $response->assertRedirect(route('chats.view', $chat->id));
    $response->assertSessionHas('success');
});

test('mengundang username yang tidak terdaftar akan memicu error validasi', function () {
    $budi = User::create(['name' => 'Budi', 'username' => 'budi', 'password' => bcrypt('password123')]);

    $chat = Chat::create(['name' => 'Grup Laravel', 'type' => 'group']);
    $chat->users()->attach($budi->id);

    // Budi mengundang user fiktif
    $response = $this->actingAs($budi)->post(route('chats.invite', $chat->id), [
        'username' => 'unknownuser',
    ]);

    $response->assertSessionHasErrors(['invite_username']);
    expect($chat->fresh()->users)->toHaveCount(1);
});

test('mengundang user yang sudah berada di grup akan memicu error validasi', function () {
    $budi = User::create(['name' => 'Budi', 'username' => 'budi', 'password' => bcrypt('password123')]);
    $andi = User::create(['name' => 'Andi', 'username' => 'andi', 'password' => bcrypt('password123')]);

    $chat = Chat::create(['name' => 'Grup Laravel', 'type' => 'group']);
    $chat->users()->attach([$budi->id, $andi->id]);

    // Budi mencoba mengundang Andi lagi
    $response = $this->actingAs($budi)->post(route('chats.invite', $chat->id), [
        'username' => 'andi',
    ]);

    $response->assertSessionHasErrors(['invite_username']);
    expect($chat->fresh()->users)->toHaveCount(2);
});

test('tidak dapat menambahkan anggota ke dalam private chat room 1-on-1', function () {
    $budi = User::create(['name' => 'Budi', 'username' => 'budi', 'password' => bcrypt('password123')]);
    $andi = User::create(['name' => 'Andi', 'username' => 'andi', 'password' => bcrypt('password123')]);
    $cindy = User::create(['name' => 'Cindy', 'username' => 'cindy', 'password' => bcrypt('password123')]);

    // Chat room private
    $chat = Chat::create(['type' => 'private']);
    $chat->users()->attach([$budi->id, $andi->id]);

    // Budi mencoba menambahkan Cindy ke chat private budi-andi
    $response = $this->actingAs($budi)->post(route('chats.invite', $chat->id), [
        'username' => 'cindy',
    ]);

    $response->assertStatus(400); // Bad request
});

test('bukan anggota grup tidak dapat mengundang user lain', function () {
    $budi = User::create(['name' => 'Budi', 'username' => 'budi', 'password' => bcrypt('password123')]);
    $andi = User::create(['name' => 'Andi', 'username' => 'andi', 'password' => bcrypt('password123')]);
    $cindy = User::create(['name' => 'Cindy', 'username' => 'cindy', 'password' => bcrypt('password123')]);

    // Grup chat budi dan andi
    $chat = Chat::create(['name' => 'Grup Belajar', 'type' => 'group']);
    $chat->users()->attach([$budi->id, $andi->id]);

    // Cindy (non-anggota) mencoba mengundang orang lain
    $response = $this->actingAs($cindy)->post(route('chats.invite', $chat->id), [
        'username' => 'budi',
    ]);

    $response->assertStatus(403); // Forbidden
});
