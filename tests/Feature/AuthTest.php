<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('halaman register dapat diakses', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
    $response->assertSee('Daftar Akun Baru');
});

test('user baru dapat mendaftar dan masuk ke dashboard', function () {
    $response = $this->post('/register', [
        'name' => 'Budi Santoso',
        'username' => 'budi',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    // Memastikan user baru tersimpan di database
    $this->assertDatabaseHas('users', [
        'name' => 'Budi Santoso',
        'username' => 'budi',
    ]);

    // Memastikan di-redirect ke dashboard dan user ter-autentikasi
    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
});

test('pendaftaran gagal jika password tidak sama', function () {
    $response = $this->post('/register', [
        'name' => 'Andi',
        'username' => 'andi',
        'password' => 'password123',
        'password_confirmation' => 'password999',
    ]);

    // Memastikan session memiliki error untuk password
    $response->assertSessionHasErrors(['password']);
    $this->assertGuest();
});
