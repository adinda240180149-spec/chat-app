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

test('halaman login dapat diakses', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSee('Selamat Datang');
});

test('user dapat login dengan kredensial yang valid', function () {
    // Membuat user percobaan
    $user = User::create([
        'name' => 'Andi Pratama',
        'username' => 'andi',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'username' => 'andi',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('user tidak dapat login dengan password salah', function () {
    // Membuat user percobaan
    $user = User::create([
        'name' => 'Andi Pratama',
        'username' => 'andi',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'username' => 'andi',
        'password' => 'passwordsalah',
    ]);

    $response->assertSessionHasErrors(['username']);
    $this->assertGuest();
});

test('user dapat logout dengan sukses', function () {
    $user = User::create([
        'name' => 'Andi Pratama',
        'username' => 'andi',
        'password' => bcrypt('password123'),
    ]);

    // Login sebagai user
    $response = $this->actingAs($user)
                     ->post('/logout');

    $response->assertRedirect(route('login'));
    $this->assertGuest();
});
