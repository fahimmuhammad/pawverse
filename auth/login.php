<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawVerse — Login</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --deep-1: #0f1724;
      --deep-2: #334155;
      --accent: #3b82f6;
    }
    html,body { font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
    .card-glass { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(10px); }
    .fade-up { transform: translateY(18px); opacity: 0; transition: all .6s cubic-bezier(.16,.84,.24,1); }
    .fade-up.in-view { transform: translateY(0); opacity: 1; }
    .form-input { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); color: white; }
  </style>
</head>
<body class="bg-gradient-to-b from-[color:var(--deep-1)] via-[color:var(--deep-2)] to-slate-700 text-slate-100 h-screen flex flex-col">

  <!-- HEADER -->
  <header class="w-full py-5 text-center text-white font-bold tracking-tight text-2xl">
    <a href="../index.php" class="hover:text-[color:var(--accent)] transition">🐾 PawVerse</a>
  </header>

  <!-- LOGIN FORM -->
  <main class="flex-1 flex items-center justify-center px-6">
    <div class="w-full max-w-md card-glass rounded-3xl p-8 fade-up shadow-2xl">
      <h2 class="text-2xl font-semibold text-center mb-2">Welcome back</h2>
      <p class="text-slate-300 text-center text-sm mb-6">Log in to continue to your PawVerse account</p>

      <form id="loginForm" class="space-y-4">
        <div>
          <label class="text-sm text-slate-300 mb-1 block">Email</label>
          <input type="email" id="l_email" name="email" required placeholder="you@example.com" class="w-full px-4 py-3 rounded-lg form-input focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
        </div>

        <div>
          <label class="text-sm text-slate-300 mb-1 block">Password</label>
          <input type="password" id="l_password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-lg form-input focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
        </div>

        <div class="flex items-center justify-between text-sm text-slate-400 mt-2">
          <label class="flex items-center gap-2">
            <input type="checkbox" class="accent-[color:var(--accent)]">
            Remember me
          </label>
          <a href="#" class="text-[color:var(--accent)] hover:underline">Forgot password?</a>
        </div>

        <button type="submit" class="w-full mt-6 bg-[color:var(--accent)] text-white py-3 rounded-lg font-semibold hover:bg-blue-500 transition">Login</button>
      </form>

      <p class="text-sm text-center text-slate-400 mt-6">
        Don’t have an account?
        <a href="register.php" class="text-[color:var(--accent)] hover:underline">Create one</a>
      </p>
    </div>
  </main>

  <!-- FOOTER -->
  <footer class="text-center text-slate-500 text-sm py-4 border-t border-white/10">
    © <?php echo date('Y'); ?> PawVerse. All rights reserved.
  </footer>

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
