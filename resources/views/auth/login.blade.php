<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | {{ config('app.name', 'Posyandu Banjar') }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
  <style>
    :root {
      --mint: #3ecf8e;
      --mint-light: #d1fae5;
      --teal: #0f9a7b;
      --deep: #023430;
      --cream: #f8fdf9;
      --warm: #fffbf0;
      --coral: #ff6b6b;
      --gold: #f5c842;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--cream);
      color: var(--deep);
      overflow-x: hidden;
    }

    /* Animated gradient mesh bg */
    .hero-bg {
      background: radial-gradient(ellipse at 20% 50%, rgba(62,207,142,0.18) 0%, transparent 60%),
                  radial-gradient(ellipse at 80% 20%, rgba(15,154,123,0.15) 0%, transparent 60%),
                  radial-gradient(ellipse at 60% 80%, rgba(245,200,66,0.1) 0%, transparent 50%),
                  linear-gradient(135deg, #f0fdf7 0%, #ecfdf5 50%, #f8fdf9 100%);
    }

    .floating {
      animation: float 6s ease-in-out infinite;
    }
    .floating-slow {
      animation: float 9s ease-in-out infinite;
    }
    .floating-rev {
      animation: floatRev 7s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-18px); }
    }
    @keyframes floatRev {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(14px); }
    }

    /* Blob shapes */
    .blob1 {
      border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
    }
    .blob2 {
      border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%;
    }

    /* Pattern dots */
    .dot-pattern {
      background-image: radial-gradient(circle, rgba(62,207,142,0.3) 1.5px, transparent 1.5px);
      background-size: 22px 22px;
    }

    .gradient-text {
      background: linear-gradient(135deg, var(--deep) 0%, var(--teal) 60%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--teal) 0%, var(--mint) 100%);
      color: white;
      padding: 12px 32px;
      border-radius: 100px;
      font-weight: 700;
      font-size: 15px;
      transition: all 0.3s ease;
      box-shadow: 0 8px 24px rgba(15,154,123,0.35);
      letter-spacing: 0.02em;
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 30px rgba(15,154,123,0.45);
    }
    .btn-primary:active {
      transform: translateY(0);
    }

    .form-input {
      width: 100%;
      padding: 14px 16px;
      padding-left: 48px;
      border-radius: 18px;
      border: 2px solid #e5e7eb;
      outline: none;
      transition: all 0.3s ease;
      background: #fdfdfd;
    }
    .form-input:focus {
      border-color: var(--mint);
      box-shadow: 0 0 0 4px rgba(62,207,142,0.1);
      background: white;
    }

    .input-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 20px;
      color: #9ca3af;
      transition: color 0.3s ease;
    }
    .form-input:focus + .input-icon {
      color: var(--teal);
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-up {
      animation: slideUp 0.6s ease-out forwards;
    }
  </style>
</head>
<body class="hero-bg min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

  <!-- Decorative background elements -->
  <div class="absolute inset-0 dot-pattern opacity-40 pointer-events-none"></div>

  <!-- Login Card -->
  <div class="w-full max-w-md relative z-10 animate-slide-up">
    <div class="bg-white rounded-[2.5rem] shadow-2xl border border-mint-100 overflow-hidden" style="border-color: rgba(62,207,142,0.2);">
      
      <!-- Top Decorative Header -->
      <div class="h-3 bg-gradient-to-r from-teal-600 to-mint-400" style="background: linear-gradient(90deg, var(--teal), var(--mint));"></div>
      
      <div class="p-8 sm:p-10">
        <!-- Logo / Brand -->
        <div class="text-center mb-10">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-6 shadow-lg shadow-mint-100" style="background: linear-gradient(135deg, var(--teal), var(--mint));">
            <svg width="32" height="32" viewBox="0 0 20 20" fill="none">
              <rect x="8" y="2" width="4" height="16" rx="2" fill="white"/>
              <rect x="2" y="8" width="16" height="4" rx="2" fill="white"/>
            </svg>
          </div>
          <h2 class="text-3xl font-black tracking-tight mb-2" style="color: var(--deep);">Selamat <span class="gradient-text">Datang</span></h2>
          <p class="text-sm font-medium opacity-60">Silakan masuk untuk mengakses sistem</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl flex items-center gap-3 text-sm font-medium animate-slide-up" style="background: var(--mint-light); color: var(--teal); border: 1px solid rgba(62,207,142,0.3);">
                <i class="mdi mdi-check-circle text-xl"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Username -->
            <div class="relative">
                <label for="username" class="block text-sm font-bold mb-2 ml-1" style="color: var(--deep);">Username</label>
                <div class="relative">
                    <input type="text" name="username" id="username" value="{{ old('username') }}" 
                        placeholder="Masukkan username" required
                        class="form-input text-sm">
                    <i class="mdi mdi-account input-icon"></i>
                </div>
                @error('username')
                    <p class="text-red-500 text-xs mt-2 ml-1 flex items-center gap-1 font-medium">
                        <i class="mdi mdi-alert-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password -->
            <div class="relative">
                <label for="password" class="block text-sm font-bold mb-2 ml-1" style="color: var(--deep);">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" 
                        placeholder="Masukkan password" required
                        class="form-input text-sm">
                    <i class="mdi mdi-lock input-icon"></i>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-2 ml-1 flex items-center gap-1 font-medium">
                        <i class="mdi mdi-alert-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Remember & Forgot -->
            <div class="flex items-center justify-between px-1">
                <label class="flex items-center gap-2 cursor-pointer select-none group">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500 transition-all">
                    <span class="text-sm font-bold opacity-60 group-hover:opacity-100 transition-opacity">Ingat Saya</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full btn-primary group">
              <span class="flex items-center justify-center gap-2">
                Masuk ke Akun
                <i class="mdi mdi-arrow-right transition-transform group-hover:translate-x-1"></i>
              </span>
            </button>
        </form>
        
        <div class="mt-10 text-center">
            <a href="/" class="inline-flex items-center gap-2 text-sm font-bold transition-all hover:gap-3" style="color: var(--teal);">
                <i class="mdi mdi-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
      </div>
    </div>
    
    <!-- Footer info -->
    <div class="text-center mt-8">
      <p class="text-xs font-medium opacity-40">© {{ date('Y') }} {{ config('app.name', 'Posyandu Banjar') }}. Hak cipta dilindungi.</p>
    </div>
  </div>

  <script>
    // Simple focus effects
    document.querySelectorAll('.form-input').forEach(el => {
      el.addEventListener('focus', () => {
        el.parentElement.querySelector('i').style.color = 'var(--teal)';
      });
      el.addEventListener('blur', () => {
        el.parentElement.querySelector('i').style.color = '#9ca3af';
      });
    });
  </script>
</body>
</html>
