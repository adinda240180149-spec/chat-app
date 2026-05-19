<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
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

    // Mulai chat personal (1-to-1) dengan mencari username
    public function startPrivateChat(Request $request)
    {
        // Validasi input username
        $request->validate([
            'username' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
        ]);

        $username = strtolower(trim($request->username));

        // 1. Cari target user di database
        $targetUser = User::where('username', $username)->first();

        if (!$targetUser) {
            return back()->withErrors(['username' => 'Username tidak ditemukan!']);
        }

        // 2. Cegah chat dengan diri sendiri
        if ($targetUser->id === Auth::id()) {
            return back()->withErrors(['username' => 'Anda tidak bisa memulai chat dengan diri sendiri!']);
        }

        // 3. Cek apakah chat private antara kedua user ini sudah ada
        $existingChat = Auth::user()->chats()
            ->where('type', 'private')
            ->whereHas('users', function ($query) use ($targetUser) {
                $query->where('users.id', $targetUser->id);
            })
            ->first();

        // 4. Jika sudah ada, langsung buka chat room tersebut
        if ($existingChat) {
            return redirect()->route('chats.view', $existingChat->id)->with('success', 'Membuka obrolan yang sudah ada.');
        }

        // 5. Jika belum ada, buat chat room private baru
        $chat = Chat::create([
            'type' => 'private',
        ]);

        // Hubungkan kedua user ke chat room ini
        $chat->users()->attach([Auth::id(), $targetUser->id]);

        // Sentuh timestamp updated_at pada chat room agar muncul di urutan teratas
        $chat->touch();

        return redirect()->route('chats.view', $chat->id)->with('success', 'Obrolan baru berhasil dimulai!');
    }

    // Tampilkan detail chat room aktif
    public function viewChat($id)
    {
        // Ambil data chat aktif beserta relasi users & messages
        $activeChat = Chat::with(['users', 'messages.user'])->findOrFail($id);

        // Keamanan: Pastikan user yang login adalah anggota dari chat room ini
        if (!$activeChat->users->contains(Auth::id())) {
            abort(403, 'Anda bukan anggota dari percakapan ini.');
        }

        // Ambil semua percakapan yang diikuti oleh user untuk merender sidebar
        $chats = Auth::user()->chats()
            ->with(['users', 'messages'])
            ->latest('updated_at')
            ->get();

        return view('dashboard', [
            'chats' => $chats,
            'activeChat' => $activeChat,
        ]);
    }

    // Kirim pesan baru ke chat room aktif via HTTP
    public function sendMessage(Request $request, $id)
    {
        $chat = Chat::findOrFail($id);

        // Keamanan: Pastikan user yang mengirim pesan adalah anggota dari chat room ini
        if (!$chat->users->contains(Auth::id())) {
            abort(403, 'Anda bukan anggota dari percakapan ini.');
        }

        // Validasi konten pesan
        $request->validate([
            'content' => 'required|string',
        ]);

        // Simpan pesan baru ke database
        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => Auth::id(),
            'content' => trim($request->content),
        ]);

        // Eager load relasi user agar nama pengirim tersedia pada payload broadcast
        $message->load('user');

        // Pancarkan event real-time dengan pengaman (try-catch) agar aplikasi tidak crash jika server WebSocket belum dijalankan di lokal
        try {
            broadcast(new \App\Events\MessageSent($message))->toOthers();
        } catch (\Exception $e) {
            // Abaikan error / catat ke log agar pengiriman chat HTTP tetap sukses dan tidak terganggu!
            report($e);
        }

        // Sentuh timestamp updated_at agar chat room ini naik ke posisi teratas daftar sidebar
        $chat->touch();

        return redirect()->route('chats.view', $chat->id);
    }

    // Buat grup chat baru
    public function createGroupChat(Request $request)
    {
        // Validasi nama grup
        $request->validate([
            'group_name' => 'required|string|max:255',
        ], [
            'group_name.required' => 'Nama grup wajib diisi.',
            'group_name.max' => 'Nama grup maksimal 255 karakter.',
        ]);

        // Buat chat room tipe grup
        $chat = Chat::create([
            'name' => trim($request->group_name),
            'type' => 'group',
        ]);

        // Hubungkan pencipta grup (user login) ke grup ini
        $chat->users()->attach(Auth::id());

        // Sentuh timestamp updated_at agar grup langsung naik ke posisi teratas
        $chat->touch();

        return redirect()->route('chats.view', $chat->id)->with('success', 'Grup obrolan baru berhasil dibuat!');
    }

    // Undang/Tambahkan user lain ke dalam grup chat aktif
    public function addUserToGroup(Request $request, $id)
    {
        $chat = Chat::findOrFail($id);

        // Pastikan chat room bertipe grup
        if ($chat->type !== 'group') {
            abort(400, 'Tidak dapat menambahkan anggota ke percakapan pribadi.');
        }

        // Keamanan: Pastikan pengundang adalah anggota grup ini
        if (!$chat->users->contains(Auth::id())) {
            abort(403, 'Anda bukan anggota dari grup ini.');
        }

        // Validasi input username
        $request->validate([
            'username' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
        ]);

        $username = strtolower(trim($request->username));

        // 1. Cari target user di database
        $targetUser = User::where('username', $username)->first();

        if (!$targetUser) {
            return back()->withErrors(['invite_username' => 'Username tidak ditemukan!']);
        }

        // 2. Cek apakah user tersebut sudah bergabung di grup
        if ($chat->users->contains($targetUser->id)) {
            return back()->withErrors(['invite_username' => 'User ini sudah bergabung di dalam grup ini!']);
        }

        // 3. Tambahkan target user ke dalam grup
        $chat->users()->attach($targetUser->id);

        // Sentuh timestamp updated_at agar chat room terupdate
        $chat->touch();

        return redirect()->route('chats.view', $chat->id)->with('success', "@{$targetUser->username} berhasil diundang ke grup!");
    }
}
