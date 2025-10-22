<?php include('../includes/db_connect.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawVerse — Login</title>

  <!-- Tailwind & Fonts -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <style>
    /* ===============================
       THEME: Light (default) + Dark Mode
       =============================== */
    :root {
      --deep-1: #ffffff;
      --deep-2: #f8fafc;
      --accent: #3b82f6;
      --text: #0f172a;
      --panel: #ffffff;
      --soft: #f1f5f9;
    }

    body.theme-dark {
      --deep-1: #0f1724;
      --deep-2: #334155;
      --accent: #3b82f6;
      --text: #e6eef8;
      --panel: rgba(255,255,255,0.05);
      --soft: rgba(255,255,255,0.03);
      background: linear-gradient(to bottom, var(--deep-1), var(--deep-2));
      color: var(--text);
    }

    body.theme-transition {
      transition: background-color 0.4s ease, color 0.4s ease;
    }

    /* Theme toggle */
    .switch {
      width: 52px;
      height: 30px;
      border-radius: 999px;
      padding: 3px;
      display: inline-flex;
      align-items: center;
      cursor: pointer;
      background: rgba(0, 0, 0, 0.1);
      transition: background 0.25s ease;
    }
    .switch .knob {
      width: 24px;
      height: 24px;
      border-radius: 999px;
      background: white;
      transform: translateX(0);
      transition: transform 0.25s ease, background 0.25s;
      box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }
    .switch.on { background: linear-gradient(90deg, var(--accent), #60a5fa); }
    .switch.on .knob { transform: translateX(22px); }

    html,body { font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
    .card-glass { background: var(--panel); border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(10px); box-shadow: 0 4px 30px rgba(0,0,0,0.08); }
    .fade-up { transform: translateY(18px); opacity: 0; transition: all .6s cubic-bezier(.16,.84,.24,1); }
    .fade-up.in-view { transform: translateY(0); opacity: 1; }
    .form-input { background: var(--soft); border: 1px solid rgba(0,0,0,0.1); color: var(--text); }
    body.theme-dark .form-input { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); color: #fff; }
  </style>
</head>

<body class="bg-[color:var(--deep-1)] text-[color:var(--text)] h-screen flex flex-col antialiased">

  <!-- HEADER -->
  <header class="w-full py-5 text-center font-bold tracking-tight text-2xl relative">
    <a href="../index.php" class="hover:text-[color:var(--accent)] transition">🐾 PawVerse</a>

    <!-- Theme Toggle (top-right corner) -->
    <div class="absolute top-5 right-6 flex items-center gap-2">
      <div class="text-xs opacity-70">☀️</div>
      <div id="themeSwitch" role="switch" aria-checked="false" tabindex="0" class="switch" title="Toggle dark mode">
        <div class="knob"></div>
      </div>
      <div class="text-xs opacity-70">🌙</div>
    </div>
  </header>

  <!-- LOGIN FORM -->
  <main class="flex-1 flex items-center justify-center px-6">
    <div class="w-full max-w-md card-glass rounded-3xl p-8 fade-up">
      <h2 class="text-2xl font-semibold text-center mb-2">Welcome back</h2>
      <p class="opacity-70 text-center text-sm mb-6">Log in to continue to your PawVerse account</p>

      <form id="loginForm" class="space-y-4">
        <div>
          <label class="text-sm opacity-80 mb-1 block">Email</label>
          <input type="email" id="l_email" name="email" required placeholder="you@example.com" class="w-full px-4 py-3 rounded-lg form-input focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
        </div>

        <div>
          <label class="text-sm opacity-80 mb-1 block">Password</label>
          <input type="password" id="l_password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-lg form-input focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
        </div>

        <div class="flex items-center justify-between text-sm opacity-70 mt-2">
          <label class="flex items-center gap-2">
            <input type="checkbox" class="accent-[color:var(--accent)]">
            Remember me
          </label>
          <a href="#" class="text-[color:var(--accent)] hover:underline">Forgot password?</a>
        </div>

        <button type="submit" class="w-full mt-6 bg-[color:var(--accent)] text-white py-3 rounded-lg font-semibold hover:bg-blue-500 transition">Login</button>
      </form>

      <p class="text-sm text-center opacity-70 mt-6">
        Don’t have an account?
        <a href="register.php" class="text-[color:var(--accent)] hover:underline">Create one</a>
      </p>
    </div>
  </main>

  <!-- FOOTER -->
  <footer class="text-center opacity-70 text-sm py-4 border-t border-white/10">
    © <?php echo date('Y'); ?> PawVerse. All rights reserved.
  </footer>

  <!-- JS -->
  <script src="../assets/js/theme.js"></script>
  <script>
    // fade in animation
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if(entry.isIntersecting) entry.target.classList.add('in-view');
      });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

    // form validation (demo)
    document.getElementById('loginForm').addEventListener('submit', e => {
      e.preventDefault();
      const email = document.getElementById('l_email').value.trim();
      const password = document.getElementById('l_password').value.trim();
      if (!email || !password) {
        alert('Please fill in all fields.');
        return;
      }

      // Demo success popup
      const notice = document.createElement('div');
      notice.className = 'fixed right-6 bottom-6 bg-emerald-600 text-white px-4 py-3 rounded-lg shadow-lg';
      notice.textContent = 'Login successful (demo). Redirecting...';
      document.body.appendChild(notice);
      setTimeout(() => notice.remove(), 4000);
    });
  </script>

</body>
</html>
