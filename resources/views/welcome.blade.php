<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatApp - Aplikasi Chat Real-Time Modern</title>
    <!-- Google Fonts Outfit & Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ff69b4;
            --primary-hover: #ff479d;
            --primary-light: #fff0f5;
            --dark: #2d3748;
            --light: #ffffff;
            --gray-100: #f7fafc;
            --gray-200: #edf2f7;
            --gray-600: #718096;
            --font-display: 'Outfit', sans-serif;
            --font-body: 'Inter', sans-serif;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--light);
            color: var(--dark);
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-display);
        }

        /* Gradient Background Accents */
        .bg-accent-1 {
            position: absolute;
            top: -200px;
            right: -200px;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 105, 180, 0.12) 0%, rgba(255, 255, 255, 0) 70%);
            z-index: -1;
            pointer-events: none;
        }

        .bg-accent-2 {
            position: absolute;
            top: 600px;
            left: -300px;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 105, 180, 0.08) 0%, rgba(255, 255, 255, 0) 70%);
            z-index: -1;
            pointer-events: none;
        }

        /* 1. Header & Navigation */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 105, 180, 0.1);
            z-index: 100;
            transition: var(--transition);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: var(--font-display);
            font-size: 24px;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }

        .logo span {
            background-color: var(--primary);
            color: var(--light);
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 800;
        }

        .logo:hover {
            transform: translateY(-1px);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn {
            font-family: var(--font-display);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-text {
            color: var(--dark);
            font-weight: 500;
        }

        .btn-text:hover {
            color: var(--primary);
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--light);
            border: none;
            box-shadow: 0 4px 14px rgba(255, 105, 180, 0.25);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 105, 180, 0.35);
        }

        .btn-outline {
            background-color: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline:hover {
            background-color: var(--primary-light);
            transform: translateY(-2px);
        }

        /* 2. Hero Section */
        .hero {
            padding: 160px 24px 80px 24px;
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            align-items: center;
            gap: 60px;
        }

        .hero-content {
            display: flex;
            flex-direction: column;
            gap: 24px;
            animation: fadeInUp 0.8s ease;
        }

        .hero-badge {
            align-self: flex-start;
            background-color: var(--primary-light);
            color: var(--primary);
            padding: 6px 16px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .hero-title {
            font-size: 52px;
            font-weight: 800;
            line-height: 1.15;
            color: var(--dark);
        }

        .hero-title span {
            color: var(--primary);
            background: linear-gradient(135deg, #ff69b4 0%, #ff1493 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-description {
            font-size: 16px;
            color: var(--gray-600);
            max-width: 500px;
            line-height: 1.7;
        }

        .hero-ctas {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-top: 8px;
        }

        /* Hero CSS Mockup Visual */
        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: float 5s ease-in-out infinite;
        }

        .mockup-container {
            width: 100%;
            max-width: 440px;
            background-color: var(--light);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.06), 0 1px 3px rgba(0,0,0,0.02);
            border: 1px solid rgba(255, 105, 180, 0.15);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 480px;
        }

        .mockup-header {
            background-color: var(--primary);
            color: var(--light);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mockup-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .mockup-info {
            flex: 1;
        }

        .mockup-info h4 {
            font-size: 14px;
            font-weight: 700;
        }

        .mockup-info p {
            font-size: 10px;
            opacity: 0.85;
        }

        .mockup-body {
            flex: 1;
            background-color: var(--gray-100);
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            overflow-y: auto;
        }

        .mockup-bubble {
            max-width: 75%;
            padding: 10px 14px;
            border-radius: 16px;
            font-size: 12px;
            line-height: 1.4;
        }

        .mockup-bubble.received {
            background-color: var(--light);
            color: var(--dark);
            align-self: flex-start;
            border: 1px solid var(--gray-200);
            border-bottom-left-radius: 4px;
        }

        .mockup-bubble.sent {
            background-color: var(--primary);
            color: var(--light);
            align-self: flex-end;
            border-bottom-right-radius: 4px;
        }

        .mockup-footer {
            padding: 12px 16px;
            background-color: var(--light);
            border-top: 1px solid var(--gray-200);
            display: flex;
            gap: 8px;
        }

        .mockup-input {
            flex: 1;
            background-color: var(--gray-100);
            border: 1px solid var(--gray-200);
            border-radius: 100px;
            padding: 8px 16px;
            font-size: 11px;
        }

        .mockup-send {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--light);
            font-size: 12px;
        }

        /* 3. Features Section */
        .features {
            padding: 100px 24px;
            background-color: var(--gray-100);
            position: relative;
        }

        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        .section-title {
            font-size: 36px;
            font-weight: 800;
            color: var(--dark);
        }

        .section-title span {
            color: var(--primary);
        }

        .section-subtitle {
            font-size: 15px;
            color: var(--gray-600);
            max-width: 600px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 32px;
        }

        .feature-card {
            background-color: var(--light);
            border-radius: 20px;
            padding: 32px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            border: 1px solid rgba(0,0,0,0.02);
            transition: var(--transition);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(255, 105, 180, 0.08);
            border-color: rgba(255, 105, 180, 0.2);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background-color: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
        }

        .feature-card h3 {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
        }

        .feature-card p {
            font-size: 14px;
            color: var(--gray-600);
            line-height: 1.6;
        }

        /* 4. CTA Section */
        .cta-section {
            padding: 100px 24px;
            text-align: center;
            position: relative;
        }

        .cta-box {
            max-width: 900px;
            margin: 0 auto;
            background: linear-gradient(135deg, rgba(255, 105, 180, 0.04) 0%, rgba(255, 20, 147, 0.02) 100%);
            border-radius: 32px;
            border: 1.5px solid rgba(255, 105, 180, 0.15);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 24px;
        }

        .cta-box h2 {
            font-size: 40px;
            font-weight: 800;
            color: var(--dark);
        }

        .cta-box p {
            font-size: 15px;
            color: var(--gray-600);
            max-width: 550px;
        }

        /* 5. Footer */
        footer {
            border-top: 1px solid var(--gray-200);
            padding: 40px 24px;
            text-align: center;
            background-color: var(--light);
        }

        .footer-logo {
            font-family: var(--font-display);
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 16px;
        }

        .footer-logo span {
            background-color: var(--primary);
            color: var(--light);
            padding: 3px 6px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 800;
        }

        .footer-copyright {
            font-size: 12px;
            color: var(--gray-600);
        }

        /* CSS Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Layouts */
        @media (max-width: 900px) {
            .hero {
                grid-template-columns: 1fr;
                text-align: center;
                padding-top: 120px;
                gap: 48px;
            }

            .hero-badge {
                align-self: center;
            }

            .hero-content {
                align-items: center;
            }

            .hero-title {
                font-size: 40px;
            }

            .cta-box h2 {
                font-size: 32px;
            }
        }

        @media (max-width: 480px) {
            .hero-ctas {
                flex-direction: column;
                width: 100%;
                gap: 12px;
            }

            .btn {
                width: 100%;
            }

            .hero-title {
                font-size: 32px;
            }
        }
    </style>
</head>
<body>

    <div class="bg-accent-1"></div>
    <div class="bg-accent-2"></div>

    <!-- 1. Header & Navigation -->
    <header>
        <div class="nav-container">
            <a href="/" class="logo">
                ChatApp <span>REAL-TIME</span>
            </a>
            <div class="nav-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Masuk Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-text">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- 2. Hero Section -->
    <main class="hero">
        <div class="hero-content">
            <div class="hero-badge">⚡ WebSocket Real-Time</div>
            <h1 class="hero-title">Mengobrol Kapan Saja, <span>Seketika Tanpa Jeda.</span></h1>
            <p class="hero-description">
                Hubungkan diri Anda dengan teman dan grup belajar secara instan. Rasakan kecepatan berkirim pesan real-time WebSocket yang sangat responsif, aman, dan tanpa hambatan.
            </p>
            <div class="hero-ctas">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Mulai Mengobrol Sekarang</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary">Mulai Gratis</a>
                    <a href="{{ route('login') }}" class="btn btn-outline">Masuk Akun</a>
                @endauth
            </div>
        </div>

        <div class="hero-visual">
            <!-- Tampilan Visual Mockup Chat Aktif -->
            <div class="mockup-container">
                <div class="mockup-header">
                    <div class="mockup-avatar">LD</div>
                    <div class="mockup-info">
                        <h4>Lecturer Demo</h4>
                        <p>Aktif Sekarang</p>
                    </div>
                </div>
                <div class="mockup-body">
                    <div class="mockup-bubble received">
                        Halo mahasiswa! Apakah fitur WebSocket-nya sudah aktif real-time?
                    </div>
                    <div class="mockup-bubble sent">
                        Sudah, Pak! Pesannya langsung terkirim seketika tanpa jeda me-refresh halaman! 🚀
                    </div>
                    <div class="mockup-bubble received">
                        Luar biasa! Desainnya juga rapi, putih bersih dikombinasikan dengan pink yang lembut!
                    </div>
                    <div class="mockup-bubble sent">
                        Terima kasih, Pak! Semua fiturnya lolos 100% tes otomatis!
                    </div>
                </div>
                <div class="mockup-footer">
                    <input type="text" class="mockup-input" placeholder="Tulis pesan ke dosen..." readonly value="Ketik pesan di sini...">
                    <div class="mockup-send">➤</div>
                </div>
            </div>
        </div>
    </main>

    <!-- 3. Features Section -->
    <section class="features">
        <div class="features-container">
            <div class="section-header">
                <h2 class="section-title">Mengapa Memilih <span>ChatApp?</span></h2>
                <p class="section-subtitle">Aplikasi chat modern yang dirancang khusus untuk memadukan performa real-time dan kemudahan pemahaman kode untuk presentasi akademik Anda.</p>
            </div>
            
            <div class="features-grid">
                <!-- Card 1: WebSocket -->
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>WebSocket Real-Time</h3>
                    <p>Sinkronisasi chat instan seketika menggunakan integrasi driver server Pusher/Reverb dan library Javascript CDN.</p>
                </div>

                <!-- Card 2: Private Chat -->
                <div class="feature-card">
                    <div class="feature-icon">👤</div>
                    <h3>Private Chat 1-on-1</h3>
                    <p>Mulai obrolan personal secara privat cukup dengan memasukkan username teman Anda. Simpel, cepat, dan terorganisir.</p>
                </div>

                <!-- Card 3: Group Chat -->
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Grup Chat Dinamis</h3>
                    <p>Buat grup diskusi dengan mudah, undang anggota baru berdasarkan username, dan lihat nama pengirim secara transparan.</p>
                </div>

                <!-- Card 4: SQLite & Clean Code -->
                <div class="feature-card">
                    <div class="feature-icon">💾</div>
                    <h3>Ringan & Portabel</h3>
                    <p>Menggunakan SQLite sebagai basis data internal yang portabel, sehingga mudah didemonstrasikan di komputer mana saja tanpa ribet.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. CTA Section -->
    <section class="cta-section">
        <div class="cta-box">
            <h2>Siap untuk Mencoba?</h2>
            <p>Daftarkan akun Anda dalam waktu kurang dari 30 detik dan langsung rasakan kenyamanan mengobrol modern secara real-time.</p>
            <div>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary" style="padding: 14px 36px; font-size: 16px;">Buka Dashboard Chat</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 14px 36px; font-size: 16px;">Mulai Mengobrol Sekarang</a>
                @endauth
            </div>
        </div>
    </section>

    <!-- 5. Footer -->
    <footer>
        <a href="/" class="footer-logo">
            ChatApp <span>REAL-TIME</span>
        </a>
        <p class="footer-copyright">&copy; 2026 ChatApp. Dibuat dengan cinta untuk tugas kuliah terbaik.</p>
    </footer>

</body>
</html>
