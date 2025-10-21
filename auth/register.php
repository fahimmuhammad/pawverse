<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawVerse — Register</title>

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

  <!-- REGISTER FORM -->
  <main class="flex-1 flex items-center justify-center px-6">
    <div class="w-full max-w-md card-glass rounded-3xl p-8 fade-up shadow-2xl">
      <h2 class="text-2xl font-semibold text-center mb-2">Create Account</h2>
      <p class="text-slate-300 text-center text-sm mb-6">Join PawVerse to shop and book vet services</p>

      <form id="registerForm" class="space-y-4">
        <div>
          <label class="text-sm text-slate-300 mb-1 block">Full Name</label>
          <input type="text" id="r_name" name="name" required placeholder="Your full name" class="w-full px-4 py-3 rounded-lg form-input focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
        </div>

        <div>
          <label class="text-sm text-slate-300 mb-1 block">Email</label>
          <input type="email" id="r_email" name="email" required placeholder="you@example.com" class="w-full px-4 py-3 rounded-lg form-input focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
        </div>

        <div>
          <label class="text-sm text-slate-300 mb-1 block">Password</label>
          <input type="password" id="r_password" name="password" required placeholder="••••••••" minlength="6" class="w-full px-4 py-3 rounded-lg form-input focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
        </div>

        <div>
          <label class="text-sm text-slate-300 mb-1 block">Confirm Password</label>
          <input type="password" id="r_confirm" name="confirm_password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-lg form-input focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
        </div>

        <div class="flex items-start gap-2 text-sm text-slate-400">
          <input type="checkbox" id="r_terms" class="mt-1 accent-[color:var(--accent)]">
          <label for="r_terms">I agree to the <a href="#" class="text-[color:var(--accent)] hover:underline">Terms & Conditions</a></label>
        </div>

        <button type="submit" class="w-full mt-4 bg-[color:var(--accent)] text-white py-3 rounded-lg font-semibold hover:bg-blue-500 transition">Register</button>
      </form>

      <p class="text-sm text-center text-slate-400 mt-6">
        Already have an account?
        <a href="login.php" class="text-[color:var(--accent)] hover:underline">Log in</a>
      </p>
    </div>
  </main>

  <!-- FOOTER -->
  <footer class="text-center text-slate-500 text-sm py-4 border-t border-white/10">
    © <?php echo date('Y'); ?> PawVerse. All rights reserved.
  </footer>

  <script>
    // fade-in animation
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if(entry.isIntersecting) entry.target.classList.add('in-view');
      });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

    // form validation demo
    document.getElementById('registerForm').addEventListener('submit', e => {
      e.preventDefault();

      const name = document.getElementById('r_name').value.trim();
      const email = document.getElementById('r_email').value.trim();
      const pass = document.getElementById('r_password').value;
      const confirm = document.getElementById('r_confirm').value;
      const terms = document.getElementById('r_terms').checked;

      if (!name || !email || !pass || !confirm) {
        alert('Please fill in all fields.');
        return;
      }

      if (pass.length < 6) {
        alert('Password must be at least 6 characters.');
        return;
      }

      if (pass !== confirm) {
        alert('Passwords do not match.');
        return;
      }

      if (!terms) {
        alert('Please accept the Terms & Conditions.');
        return;
      }

      // Demo success popup
      const notice = document.createElement('div');
      notice.className = 'fixed right-6 bottom-6 bg-emerald-600 text-white px-4 py-3 rounded-lg shadow-lg';
      notice.textContent = 'Account created successfully (demo).';
      document.body.appendChild(notice);
      setTimeout(() => notice.remove(), 4000);

      e.target.reset();
    });
  </script>

</body>
</html>
