<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Inventaris</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #14122b;
            --muted: #6b6a80;
            --line: #e4e3ef;
            --bg: #f6f5fb;
            --primary: #5b4bff;
            --primary-dark: #4536e0;
            --danger: #e5384f;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--ink);
            background: var(--bg);
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.1fr 1fr;
        }

        /* Panel kiri */
        .hero {
            position: relative;
            overflow: hidden;
            padding: 56px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #fff;
            background: #1a1447;
        }
        .hero::before, .hero::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            opacity: .85;
            animation: float 14s ease-in-out infinite alternate;
        }
        .hero::before { width: 480px; height: 480px; background: #5b4bff; top: -120px; left: -100px; }
        .hero::after  { width: 420px; height: 420px; background: #ff5fa2; bottom: -140px; right: -80px; animation-delay: -6s; }
        @keyframes float {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(60px, 40px) scale(1.15); }
        }
        .hero > * { position: relative; z-index: 1; }
        .brand { font-family: 'Sora', sans-serif; font-weight: 700; font-size: 1.25rem; }
        .hero h1 { font-family: 'Sora', sans-serif; font-size: clamp(2rem, 3.4vw, 3rem); line-height: 1.15; max-width: 14ch; }
        .hero p { margin-top: 16px; max-width: 38ch; color: rgba(255,255,255,.8); line-height: 1.6; }
        .hero small { color: rgba(255,255,255,.6); }

        /* Panel kanan */
        .panel { display: grid; place-items: center; padding: 32px; }
        .card { width: 100%; max-width: 400px; animation: rise .6s ease both; }
        @keyframes rise { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
        .card h2 { font-family: 'Sora', sans-serif; font-size: 1.8rem; }
        .card .sub { color: var(--muted); margin: 8px 0 28px; }

        .alert {
            background: #fdecef; color: var(--danger);
            border: 1px solid #f7c6cf; border-radius: 12px;
            padding: 12px 14px; font-size: .9rem; margin-bottom: 18px;
        }
        .field { margin-bottom: 18px; }
        label { display: block; font-size: .85rem; font-weight: 500; margin-bottom: 8px; }
        .input-wrap { position: relative; }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 14px 16px;
            font: inherit;
            color: var(--ink);
            background: #fff;
            border: 1.5px solid var(--line);
            border-radius: 12px;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        input:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(91,75,255,.15); }
        input.is-invalid { border-color: var(--danger); }
        .error { color: var(--danger); font-size: .82rem; margin-top: 6px; }
        .toggle {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: 0; cursor: pointer;
            color: var(--muted); font-size: .8rem; font-weight: 500; padding: 6px;
        }
        .toggle:hover { color: var(--primary); }

        .btn {
            width: 100%; padding: 14px; margin-top: 6px;
            font: 600 1rem 'Sora', sans-serif;
            color: #fff; background: var(--primary);
            border: 0; border-radius: 12px; cursor: pointer;
            box-shadow: 0 10px 24px -10px rgba(91,75,255,.7);
            transition: background .2s, transform .15s;
        }
        .btn:hover { background: var(--primary-dark); }
        .btn:active { transform: scale(.98); }
        .btn:disabled { opacity: .7; cursor: wait; }

        :focus-visible { outline: 3px solid rgba(91,75,255,.5); outline-offset: 2px; }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }
        @media (max-width: 860px) {
            body { grid-template-columns: 1fr; }
            .hero { padding: 32px; min-height: 240px; }
            .hero p, .hero small { display: none; }
        }
    </style>
</head>
<body>

    <aside class="hero">
        <div class="brand">◆ Inventaris</div>
        <div>
            <h1>Sistem Inventaris</h1>
            <p>Masuk untuk mengelola data barang dan stok dengan mudah dan aman.</p>
        </div>
        <small>&copy; {{ date('Y') }} Inventaris</small>
    </aside>

    <main class="panel">
        <div class="card">
            <h2>LOGIN INVENTARIS</h2>
            <p class="sub">Masukkan username dan password kamu.</p>

            @if (session('error'))
                <div class="alert">{{ session('error') }}</div>
            @endif

            @if (session('status'))
                <div class="alert" style="background:#e9f8ef;color:#1a7f43;border-color:#bfe8cf">{{ session('status') }}</div>
            @endif

            {{-- form login --}}
            <form action="/login" method="POST" id="loginForm">
                @csrf

                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}"
                           placeholder="Masukkan username" autocomplete="username" required autofocus
                           class="@error('username') is-invalid @enderror">
                    @error('username') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <input type="password" id="password" name="password"
                               placeholder="Masukkan password" autocomplete="current-password" required
                               class="@error('password') is-invalid @enderror">
                        <button type="button" class="toggle" id="toggle" aria-label="Tampilkan password">Lihat</button>
                    </div>
                    @error('password') <div class="error">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn" id="submitBtn">Login</button>
            </form>
            {{-- --------------------------- --}}
        </div>
    </main>

    <script>
        const pw = document.getElementById('password');
        const toggle = document.getElementById('toggle');
        toggle.addEventListener('click', () => {
            const show = pw.type === 'password';
            pw.type = show ? 'text' : 'password';
            toggle.textContent = show ? 'Sembunyi' : 'Lihat';
        });

        document.getElementById('loginForm').addEventListener('submit', () => {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.textContent = 'Memproses...';
        });
    </script>
</body>
</html>