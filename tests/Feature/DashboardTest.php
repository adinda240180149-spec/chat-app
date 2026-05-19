<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('tamu tidak dapat mengakses dashboard dan diarahkan ke login', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect(route('login'));
});

test('user terautentikasi dapat melihat dashboard dengan navbar pink', function () {
    $user = User::create([
        'name' => 'Budi Santoso',
        'username' => 'budi',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    // Verifikasi bahwa nama dan username user tampil
    $response->assertSee('Budi Santoso');
    $response->assertSee('@budi');
    // Verifikasi teks empty state chat
    $response->assertSee('Mari mengobrol!');
    $response->assertSee('Pilih percakapan yang sudah ada di sidebar kiri');
});
