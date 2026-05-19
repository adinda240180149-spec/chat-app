<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Chat - ChatApp</title>
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #fafafa;
            color: #333333;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* 1. Navbar Pink */
        .navbar {
            background-color: #ff69b4;
            color: #ffffff;
            height: 64px;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(255, 105, 180, 0.15);
            z-index: 10;
        }

        .navbar-brand {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-brand span {
            background-color: #ffffff;
            color: #ff69b4;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 800;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-info {
            font-size: 14px;
            font-weight: 500;
            text-align: right;
        }

        .user-info small {
            display: block;
            opacity: 0.85;
            font-size: 11px;
        }

        .btn-logout {
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background-color: #ffffff;
            color: #ff69b4;
            border-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* 2. Main Layout (Master-Detail) */
        .main-container {
            flex: 1;
            display: flex;
            overflow: hidden;
        }

        /* Sidebar Kiri (Latar Belakang Putih) */
        .sidebar {
            width: 350px;
            background-color: #ffffff;
            border-right: 1px solid #f0f0f0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #f9f9f9;
        }

        .sidebar-title {
            font-size: 18px;
            font-weight: 700;
            color: #333333;
            margin-bottom: 15px;
        }

        /* Tombol Tambah Chat / Grup */
        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            flex: 1;
            padding: 10px;
            background-color: #ffffff;
            color: #ff69b4;
            border: 1.5px solid #ffe2ed;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }

        .btn-action:hover {
            background-color: #fff5f8;
            border-color: #ff69b4;
        }

        /* Panel Input Cari User / Buat Grup */
        .form-panel {
            background-color: #fffafb;
            border: 1px solid #ffeaf0;
            border-radius: 8px;
            padding: 12px;
            margin-top: 12px;
            display: none; /* Disembunyikan secara default, di-toggle Javascript */
        }

        .form-panel.active {
            display: block;
        }

        .form-panel h3 {
            font-size: 13px;
            font-weight: 700;
            color: #ff69b4;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .form-panel-group {
            display: flex;
            gap: 8px;
        }

        .form-panel-input {
            flex: 1;
            padding: 8px 12px;
            font-size: 13px;
            border: 1.5px solid #e2e8f0;
            border-radius: 6px;
            outline: none;
        }

        .form-panel-input:focus {
            border-color: #ff69b4;
        }

        .btn-submit {
            background-color: #ff69b4;
            color: #ffffff;
            border: none;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-submit:hover {
            background-color: #ff479d;
        }

        /* Daftar Percakapan */
        .chat-list {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
        }

        .chat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 12px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 6px;
            border: 1px solid transparent;
            text-decoration: none;
            color: inherit;
        }

        .chat-item:hover {
            background-color: #fff9fa;
            border-color: #ffeef2;
        }

        .chat-item.active {
            background-color: #ffeef2;
            border-color: #ffccd8;
        }

        /* Avatar */
        .avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: #ffe2ed;
            color: #ff69b4;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            font-size: 16px;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 5px rgba(255, 105, 180, 0.1);
        }

        .avatar-group {
            background-color: #e3f2fd;
            color: #1e88e5;
        }

        .chat-info {
            flex: 1;
            min-width: 0;
        }

        .chat-info-top {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 4px;
        }

        .chat-name {
            font-size: 14px;
            font-weight: 600;
            color: #333333;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-time {
            font-size: 11px;
            color: #999999;
            flex-shrink: 0;
        }

        .chat-last-message {
            font-size: 13px;
            color: #777777;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .empty-chat-list {
            text-align: center;
            color: #888888;
            font-size: 13px;
            margin-top: 50px;
            padding: 0 20px;
            line-height: 1.6;
        }

        /* Panel Kanan (Isi Obrolan - Latar Belakang Putih) */
        .chat-area {
            flex: 1;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Tampilan Placeholder Chat Kosong */
        .chat-empty-state {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px;
            background-color: #fafafa;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #ffffff;
            border: 2px dashed #ffb3d1;
            color: #ff69b4;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 32px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        }

        .chat-empty-state h2 {
            font-size: 20px;
            font-weight: 700;
            color: #333333;
            margin-bottom: 10px;
        }

        .chat-empty-state p {
            font-size: 14px;
            color: #777777;
            max-width: 400px;
            line-height: 1.5;
        }

        /* Notifikasi Flash */
        .toast-success {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #2f855a;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            z-index: 100;
            animation: slideIn 0.3s ease, fadeOut 0.3s ease 2.7s forwards;
        }

        @keyframes slideIn {
            from { transform: translateX(120%); }
            to { transform: translateX(0); }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; visibility: hidden; }
        }

        /* 3. Obrolan Aktif (Stage 8) */
        .chat-header {
            height: 64px;
            border-bottom: 1px solid #f0f0f0;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
        }

        .chat-header-title {
            font-size: 16px;
            font-weight: 700;
            color: #333333;
        }

        .chat-header-subtitle {
            font-size: 11px;
            color: #ff69b4;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background-color: #fafafa;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .message-row {
            display: flex;
            width: 100%;
        }

        .message-row.sent {
            justify-content: flex-end;
        }

        .message-row.received {
            justify-content: flex-start;
        }

        .message-bubble {
            max-width: 65%;
            padding: 10px 14px;
            font-size: 14px;
            line-height: 1.5;
            position: relative;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .message-row.sent .message-bubble {
            background-color: #ff69b4;
            color: #ffffff;
            border-radius: 16px 16px 4px 16px;
        }

        .message-row.received .message-bubble {
            background-color: #ffffff;
            color: #333333;
            border: 1px solid #eeeeee;
            border-radius: 16px 16px 16px 4px;
        }

        .message-sender {
            font-size: 11px;
            font-weight: 600;
            color: #ff69b4;
            margin-bottom: 4px;
        }

        .message-time {
            font-size: 10px;
            text-align: right;
            margin-top: 4px;
            opacity: 0.7;
        }

        .message-row.sent .message-time {
            color: rgba(255, 255, 255, 0.8);
        }

        .message-row.received .message-time {
            color: #999999;
        }

        .chat-footer {
            padding: 15px 20px;
            background-color: #ffffff;
            border-top: 1px solid #f0f0f0;
        }

        .message-form {
            display: flex;
            gap: 10px;
        }

        .message-input {
            flex: 1;
            padding: 12px 16px;
            font-size: 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            outline: none;
            transition: border-color 0.2s;
        }

        .message-input:focus {
            border-color: #ff69b4;
        }

        .btn-send {
            background-color: #ff69b4;
            color: #ffffff;
            border: none;
            padding: 0 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-send:hover {
            background-color: #ff479d;
        }
    </style>
</head>
<body>

    <!-- Notifikasi Flash jika ada sukses -->
    @if (session('success'))
        <div class="toast-success" id="successToast">
            {{ session('success') }}
        </div>
    @endif

    <!-- 1. Navbar Pink -->
    <header class="navbar">
        <div class="navbar-brand">
            ChatApp <span>REAL-TIME</span>
        </div>
        <div class="navbar-user">
            <div class="user-info">
                {{ Auth::user()->name }}
                <small>{{ '@' . Auth::user()->username }}</small>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Keluar</button>
            </form>
        </div>
    </header>

    <!-- 2. Kontainer Utama -->
    <div class="main-container">
        
        <!-- Sidebar Kiri (Latar Belakang Putih) -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2 class="sidebar-title">Percakapan</h2>
                
                <div class="action-buttons">
                    <button class="btn-action" onclick="toggleForm('formPrivate')">+ Chat Personal</button>
                    <button class="btn-action" onclick="toggleForm('formGroup')">+ Buat Grup</button>
                </div>

                <!-- Form Panel Chat Personal -->
                <div class="form-panel" id="formPrivate" style="{{ $errors->has('username') ? 'display: block;' : '' }}">
                    <h3>Tambah Chat Personal</h3>
                    <form action="{{ route('chats.private') }}" method="POST">
                        @csrf
                        <div class="form-panel-group">
                            <input type="text" name="username" class="form-panel-input" placeholder="Ketik username teman..." required value="{{ old('username') }}">
                            <button type="submit" class="btn-submit">Cari</button>
                        </div>
                        @error('username')
                            <span class="error-message" style="color: #e53e3e; font-size: 12px; margin-top: 6px; display: block; font-weight: 500;">{{ $message }}</span>
                        @enderror
                    </form>
                </div>

                <!-- Form Panel Buat Grup (Akan Berfungsi di Stage 9) -->
                <div class="form-panel" id="formGroup">
                    <h3>Buat Grup Obrolan</h3>
                    <form action="#" method="POST">
                        @csrf
                        <div class="form-panel-group">
                            <input type="text" name="group_name" class="form-panel-input" placeholder="Nama grup baru..." required>
                            <button type="submit" class="btn-submit">Buat</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Daftar Chat -->
            <div class="chat-list">
                @if ($chats->isEmpty())
                    <div class="empty-chat-list">
                        Belum ada obrolan.<br>
                        Mulai dengan mengetuk tombol <strong>+ Chat Personal</strong> atau <strong>+ Buat Grup</strong> di atas.
                    </div>
                @else
                    @foreach ($chats as $chat)
                        @php
                            // Ambil nama chat & inisial avatar
                            if ($chat->type === 'private') {
                                $otherUser = $chat->users->where('id', '!=', Auth::id())->first();
                                $chatName = $otherUser ? $otherUser->name : 'Akun Terhapus';
                                $avatarInitial = strtoupper(substr($chatName, 0, 1));
                                $isGroup = false;
                            } else {
                                $chatName = $chat->name;
                                $avatarInitial = strtoupper(substr($chatName, 0, 1));
                                $isGroup = true;
                            }
                            $lastMsg = $chat->messages->last();
                            $lastMsgContent = $lastMsg ? $lastMsg->content : 'Belum ada pesan.';
                            $lastMsgTime = $lastMsg ? $lastMsg->created_at->format('H:i') : '';
                        @endphp
                        
                        <a href="{{ route('chats.view', $chat->id) }}" class="chat-item {{ $activeChat && $activeChat->id === $chat->id ? 'active' : '' }}">
                            <div class="avatar {{ $isGroup ? 'avatar-group' : '' }}">
                                {{ $avatarInitial }}
                            </div>
                            <div class="chat-info">
                                <div class="chat-info-top">
                                    <div class="chat-name">{{ $chatName }}</div>
                                    <div class="chat-time">{{ $lastMsgTime }}</div>
                                </div>
                                <div class="chat-last-message">{{ $lastMsgContent }}</div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </aside>

        <!-- Panel Kanan (Isi Obrolan) -->
        <main class="chat-area">
            @if ($activeChat === null)
                <!-- Tampilan Kosong Default -->
                <div class="chat-empty-state">
                    <div class="empty-state-icon">💬</div>
                    <h2>Mari mengobrol!</h2>
                    <p>Pilih percakapan yang sudah ada di sidebar kiri, atau ketuk tombol tambah untuk memulai percakapan baru.</p>
                </div>
            @else
                <!-- Header Chat Aktif -->
                @php
                    if ($activeChat->type === 'private') {
                        $otherUser = $activeChat->users->where('id', '!=', Auth::id())->first();
                        $activeChatName = $otherUser ? $otherUser->name : 'Akun Terhapus';
                        $subtitle = 'Obrolan Pribadi';
                    } else {
                        $activeChatName = $activeChat->name;
                        $subtitle = 'Grup Obrolan';
                    }
                @endphp
                <div class="chat-header">
                    <div>
                        <h2 class="chat-header-title">{{ $activeChatName }}</h2>
                        <div class="chat-header-subtitle">{{ $subtitle }}</div>
                    </div>
                </div>

                <!-- Daftar Pesan -->
                <div class="chat-messages" id="chatMessages">
                    @if ($activeChat->messages->isEmpty())
                        <div style="text-align: center; color: #888888; font-size: 13px; margin-top: auto; margin-bottom: auto; padding: 20px;">
                            Belum ada pesan di sini. Kirim pesan pertama Anda untuk memulai percakapan!
                        </div>
                    @else
                        @foreach ($activeChat->messages as $message)
                            @php
                                $isSentByMe = $message->user_id === Auth::id();
                            @endphp
                            <div class="message-row {{ $isSentByMe ? 'sent' : 'received' }}">
                                <div class="message-bubble">
                                    @if ($activeChat->type === 'group' && !$isSentByMe)
                                        <div class="message-sender">{{ $message->user->name }}</div>
                                    @endif
                                    <div class="message-content">{{ $message->content }}</div>
                                    <div class="message-time">{{ $message->created_at->format('H:i') }}</div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Input Footer -->
                <div class="chat-footer">
                    <form action="{{ route('messages.send', $activeChat->id) }}" method="POST" class="message-form">
                        @csrf
                        <input type="text" name="content" class="message-input" placeholder="Tulis pesan..." autocomplete="off" required>
                        <button type="submit" class="btn-send">Kirim</button>
                    </form>
                </div>
            @endif
        </main>

    </div>

    <!-- Javascript Sederhana untuk Toggle Form Modal -->
    <script>
        function toggleForm(formId) {
            // Tutup form lainnya
            const forms = ['formPrivate', 'formGroup'];
            forms.forEach(id => {
                if (id !== formId) {
                    const el = document.getElementById(id);
                    if (el) el.style.display = 'none';
                }
            });

            // Toggle form terpilih
            const element = document.getElementById(formId);
            if (element) {
                if (element.style.display === 'block') {
                    element.style.display = 'none';
                } else {
                    element.style.display = 'block';
                    element.querySelector('input').focus();
                }
            }
        }

        // Otomatis hilangkan toast setelah 3 detik
        const toast = document.getElementById('successToast');
        if (toast) {
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        // Otomatis scroll chat ke bawah saat halaman dimuat
        const chatMessages = document.getElementById('chatMessages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    </script>
</body>
</html>
