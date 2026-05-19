<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Tampilkan halaman dashboard utama
    public function index()
    {
        // Ambil semua percakapan yang diikuti oleh user yang sedang login
        $chats = Auth::user()->chats()
            ->with(['users', 'messages'])
            ->latest('updated_at')
            ->get();

        return view('dashboard', [
            'chats' => $chats,
            'activeChat' => null, // Belum ada chat aktif yang dipilih
        ]);
    }
}
