<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel Chats (Wadah percakapan)
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Nama grup (NULL jika chat personal/private)
            $table->string('type')->default('private'); // 'private' atau 'group'
            $table->timestamps();
        });

        // 2. Tabel Pivot Chat_User (Daftar peserta di dalam setiap Chat)
        Schema::create('chat_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // 3. Tabel Messages (Pesan-pesan dalam Chat)
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pengirim pesan
            $table->text('content'); // Isi pesan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('chat_user');
        Schema::dropIfExists('chats');
    }
};
