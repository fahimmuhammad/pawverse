<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawVerse — Contact</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="assets/images/logo.png">

  <style>
    :root {
      /* Light Mode */
      --bg: #f8fafc;
      --text: #0f1724;
      --muted: #475569;
      --panel: #ffffff;
      --accent: #3b82f6;
      --footer-bg: #0f1724;
      --footer-text: #cbd5e1;
    }

    .theme-dark {
      /* Dark Mode */
      --bg: linear-gradient(135deg, #0f1724, #334155);
      --text: #e6eef8;
      --muted: #94a3b8;
      --panel: rgba(255, 255, 255, 0.05);
      --accent: #3b82f6;
      --footer-bg: #0b1220;
      --footer-text: #94a3b8;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: "Inter", sans-serif;
      transition: background-color 0.4s, color 0.4s;
    }

    .panel { background: var(--panel); transition: background-color 0.4s, color 0.4s; }
    .muted { color: var(--muted); }
    .accent { color: var(--accent); }

    .card-glass {
      background: var(--panel);
      border: 1px solid rgba(255,255,255,0.08);
      backdrop-filter: blur(8px);
      transition: all 0.4s ease;
    }

    /* Switch toggle */
    .switch {
      width: 52px; height: 30px;
      border-radius: 999px;
      padding: 3px;
      display: inline-flex;
      align-items: center;
      cursor: pointer;
      background: rgba(0, 0, 0, 0.06);
      transition: background 0.25s ease;
    }
    .switch .knob {
      width: 24px; height: 24px;
      border-radius: 999px;
      background: white;
      transform: translateX(0);
      transition: transform 0.25s ease, background 0.25s;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    .switch.on { background: linear-gradient(90deg, var(--accent), #60a5fa); }
    .switch.on .knob { transform: translateX(22px); background: white; }

    .fade-up { transform: translateY(18px); opacity: 0; transition: all .6s cubic-bezier(.16,.84,.24,1); }
    .fade-up.in-view { transform: translateY(0); opacity: 1; }
  </style>
</head>

<body>

  <!-- NAV -->
  <header class="fixed w-full z-40 panel border-b border-white/10 shadow">
    <div class="max-w-7xl mx-auto px-6">
      <nav class="flex items-center justify-between py-4">
        <a href="index.php" class="flex items-center gap-3">
          <img src="assets/images/logo.png" alt="PawVerse Logo" class="w-10 h-10 rounded-lg">
          <div>
            <h1 class="text-xl font-bold tracking-tight leading-none text-[color:var(--accent)]">PawVerse</h1>
            <p class="text-xs muted -mt-1">Care • Comfort • Community</p>
          </div>
        </a>

        <div class="hidden md:flex items-center gap-6">
          <a href="index.php" class="muted hover:text-[color:var(--accent)]">Home</a>
          <a href="products.php" class="muted hover:text-[color:var(--accent)]">Products</a>
          <a href="services.php" class="muted hover:text-[color:var(--accent)]">Services</a>
          <a href="about.php" class="muted hover:text-[color:var(--accent)]">About</a>
          <a href="contact.php" class="font-semibold text-[color:var(--accent)]">Contact</a>
          <a href="auth/login.php" class="ml-2 bg-[color:var(--accent)] text-white px-4 py-2 rounded-lg hover:opacity-90 transition">Log in</a>

          <!-- Theme Toggle -->
          <div class="flex items-center gap-2 ml-4">
            <div class="text-xs muted">☀️</div>
            <div id="themeSwitch" role="switch" aria-checked="false" tabindex="0" class="switch" title="Toggle dark mode">
              <div class="knob"></div>
            </div>
            <div class="text-xs muted">🌙</div>
          </div>
        </div>

        <div class="md:hidden">
          <button aria-label="open menu" id="mobileToggle" class="p-2 rounded-lg bg-[color:var(--accent)]/10">
            <svg width="22" height="22" viewBox="0 0 24 24" class="text-[color:var(--accent)]" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
          </button>
        </div>
      </nav>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="md:hidden hidden bg-[rgba(8,10,20,0.3)] backdrop-blur-md border-t border-white/10">
      <div class="px-6 py-4 flex flex-col gap-3">
        <a href="index.php" class="muted">Home</a>
        <a href="products.php" class="muted">Products</a>
        <a href="services.php" class="muted">Services</a>
        <a href="about.php" class="muted">About</a>
        <a href="contact.php" class="text-[color:var(--accent)] font-semibold">Contact</a>
        <a href="auth/login.php" class="mt-2 inline-block bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white text-center">Log in</a>
      </div>
    </div>
  </header>

  <main class="pt-28">

    <!-- HERO -->
    <section class="text-center py-20 bg-[color:var(--panel)] fade-up">
      <h2 class="text-4xl md:text-5xl font-extrabold mb-4">Let’s stay in touch 🐾</h2>
      <p class="muted text-lg max-w-2xl mx-auto">We’d love to hear from you — whether it’s a question, feedback, or a simple hello!</p>
    </section>

    <!-- CONTACT INFO -->
    <section class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="card-glass rounded-2xl p-6 text-center fade-up">
        <h4 class="font-semibold mb-2">📍 Address</h4>
        <p class="muted text-sm">PawVerse HQ<br>Worldwide Virtual Presence</p>
      </div>
      <div class="card-glass rounded-2xl p-6 text-center fade-up" data-delay="120">
        <h4 class="font-semibold mb-2">📧 Email</h4>
        <p class="muted text-sm">support@pawverse.com</p>
      </div>
      <div class="card-glass rounded-2xl p-6 text-center fade-up" data-delay="180">
        <h4 class="font-semibold mb-2">🕒 Hours</h4>
        <p class="muted text-sm">Monday – Saturday<br>9:00 AM – 8:00 PM</p>
      </div>
    </section>

    <!-- CONTACT FORM -->
    <section class="max-w-4xl mx-auto px-6 py-10">
      <div class="p-8 rounded-3xl card-glass shadow-lg fade-up" data-delay="200">
        <h3 class="text-2xl font-semibold mb-6 text-center">Send us a message</h3>
        <form id="contactForm" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input id="c_name" type="text" placeholder="Your Name" required class="px-4 py-3 rounded-lg border border-white/10 bg-[color:var(--panel)] text-[color:var(--text)] placeholder-[color:var(--muted)] focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
            <input id="c_email" type="email" placeholder="Your Email" required class="px-4 py-3 rounded-lg border border-white/10 bg-[color:var(--panel)] text-[color:var(--text)] placeholder-[color:var(--muted)] focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
          </div>
          <input id="c_subject" type="text" placeholder="Subject" class="w-full px-4 py-3 rounded-lg border border-white/10 bg-[color:var(--panel)] text-[color:var(--text)] placeholder-[color:var(--muted)] focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
          <textarea id="c_message" rows="5" placeholder="Your message..." required class="w-full px-4 py-3 rounded-lg border border-white/10 bg-[color:var(--panel)] text-[color:var(--text)] placeholder-[color:var(--muted)] focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none"></textarea>
          <div class="text-center">
            <button type="submit" class="bg-[color:var(--accent)] px-6 py-3 rounded-lg text-white font-semibold hover:opacity-90 transition">Send Message</button>
          </div>
        </form>
      </div>
    </section>

    <!-- MAP -->
    <section class="max-w-6xl mx-auto px-6 pb-20">
      <div class="rounded-3xl overflow-hidden shadow-lg fade-up" data-delay="240">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d19861.24313777076!2d-0.127758!3d51.507351!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48761b334a8d6e09%3A0x9ad740d64b8ad509!2sGlobal%20Map!5e0!3m2!1sen!2s!4v1692030553810!5m2!1sen!2s"
          width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
      </div>
    </section>
  </main>

  <!-- FOOTER -->
  <footer class="pt-10 pb-6" style="background: var(--footer-bg); color: var(--footer-text);">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div><h4 class="text-xl font-bold text-white mb-3">PawVerse</h4><p>Trusted pet products & veterinary services.</p></div>
      <div>
        <h5 class="font-semibold mb-2">Explore</h5>
        <ul class="space-y-1 text-sm">
          <li><a href="products.php">Shop</a></li>
          <li><a href="services.php">Services</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>
      <div><h5 class="font-semibold mb-2">Contact</h5><p class="text-sm">support@pawverse.com<br/>+880 1XXX-XXXXXX</p></div>
    </div>
    <div class="text-center text-sm mt-8 border-t border-white/10 pt-4">
      © <?php echo date('Y'); ?> PawVerse. All rights reserved.
    </div>
  </footer>

  <!-- Global Theme -->
  <script src="assets/js/theme.js"></script>

  <script>
    // mobile toggle
    document.getElementById('mobileToggle').addEventListener('click', () => {
      document.getElementById('mobileMenu').classList.toggle('hidden');
    });

    // fade animations
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('in-view'); });
    }, { threshold: 0.12 });
    document.querySelectorAll('.fade-up').forEach((el, idx) => {
      el.style.transitionDelay = `${idx * 60}ms`;
      observer.observe(el);
    });

    // contact form demo
    document.getElementById('contactForm').addEventListener('submit', e => {
      e.preventDefault();
      const name = c_name.value.trim(), email = c_email.value.trim(), message = c_message.value.trim();
      if (!name || !email || !message) return alert('Please fill in all required fields.');

      const notice = document.createElement('div');
      notice.className = 'fixed right-6 bottom-6 bg-emerald-600 text-white px-4 py-3 rounded-lg shadow-lg';
      notice.textContent = 'Message sent (demo). Thank you for contacting PawVerse!';
      document.body.appendChild(notice);
      setTimeout(() => notice.remove(), 4000);
      e.target.reset();
    });
  </script>
</body>
</html>
