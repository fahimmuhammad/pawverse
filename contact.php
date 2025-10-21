<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawVerse — Contact</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --deep-1: #0f1724;
      --deep-2: #334155;
      --cream: #fbfaf8;
      --accent: #3b82f6;
    }
    html,body { font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
    .card-glass { background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); border: 1px solid rgba(255,255,255,0.04); backdrop-filter: blur(6px); }
    .fade-up { transform: translateY(18px); opacity: 0; transition: all .6s cubic-bezier(.16,.84,.24,1); }
    .fade-up.in-view { transform: translateY(0); opacity: 1; }
    .hero-parallax { will-change: transform; }
  </style>
</head>
<body class="bg-gradient-to-b from-[color:var(--deep-1)] via-[color:var(--deep-2)] to-slate-700 text-slate-100 antialiased">

  <!-- NAV -->
  <header class="fixed w-full z-40">
    <div class="max-w-7xl mx-auto px-6">
      <nav class="flex items-center justify-between py-4">
        <a href="index.php" class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-white/8 flex items-center justify-center shadow-sm">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 12C3 8 7 3 12 3s9 5 9 9-4 9-9 9S3 16 3 12z" stroke="white" stroke-opacity=".9" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <div>
            <h1 class="text-xl font-bold tracking-tight leading-none">PawVerse</h1>
            <p class="text-xs text-slate-300 -mt-1">Care • Comfort • Community</p>
          </div>
        </a>

        <div class="hidden md:flex items-center gap-6">
          <a href="index.php" class="text-slate-200 hover:text-white">Home</a>
          <a href="products.php" class="text-slate-200 hover:text-white">Products</a>
          <a href="services.php" class="text-slate-200 hover:text-white">Services</a>
          <a href="about.php" class="text-slate-200 hover:text-white">About</a>
          <a href="contact.php" class="text-white font-semibold">Contact</a>
          <a href="auth/login.php" class="ml-4 inline-flex items-center gap-2 bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white font-medium shadow-sm hover:bg-blue-500 transition">Log in</a>
        </div>

        <div class="md:hidden">
          <button aria-label="open menu" id="mobileToggle" class="p-2 rounded-lg bg-white/6">
            <svg width="22" height="22" viewBox="0 0 24 24" class="text-white" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 7h16M4 12h16M4 17h16" stroke="white" stroke-width="1.6" stroke-linecap="round"/></svg>
          </button>
        </div>
      </nav>
    </div>

    <div id="mobileMenu" class="md:hidden hidden bg-[rgba(8,10,20,0.4)] backdrop-blur-sm border-t border-white/6">
      <div class="px-6 py-4 flex flex-col gap-3">
        <a href="index.php" class="text-slate-200">Home</a>
        <a href="products.php" class="text-slate-200">Products</a>
        <a href="services.php" class="text-slate-200">Services</a>
        <a href="about.php" class="text-slate-200">About</a>
        <a href="contact.php" class="text-white font-semibold">Contact</a>
        <a href="auth/login.php" class="mt-2 inline-block bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white text-center">Log in</a>
      </div>
    </div>
  </header>

  <main class="pt-28">

    <!-- HERO -->
    <section class="relative overflow-hidden">
      <div class="absolute inset-0 -z-10 bg-gradient-to-b from-slate-900/70 to-slate-700/70"></div>
      <div class="max-w-7xl mx-auto px-6 py-20 text-center">
        <h2 class="text-4xl md:text-5xl font-extrabold text-white fade-up">Let’s stay in touch 🐾</h2>
        <p class="text-slate-300 mt-4 max-w-2xl mx-auto fade-up" data-delay="100">
          We’d love to hear from you — whether it’s a question, feedback, or a simple hello!
        </p>
      </div>
    </section>

    <!-- CONTACT INFO -->
    <section class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="card-glass rounded-2xl p-6 fade-up">
        <h4 class="text-white font-semibold mb-2">📍 Address</h4>
        <p class="text-slate-300 text-sm">PawVerse HQ<br>Worldwide Virtual Presence</p>
      </div>
      <div class="card-glass rounded-2xl p-6 fade-up" data-delay="120">
        <h4 class="text-white font-semibold mb-2">📧 Email</h4>
        <p class="text-slate-300 text-sm">support@pawverse.com</p>
      </div>
      <div class="card-glass rounded-2xl p-6 fade-up" data-delay="180">
        <h4 class="text-white font-semibold mb-2">🕒 Hours</h4>
        <p class="text-slate-300 text-sm">Monday – Saturday<br>9:00 AM – 8:00 PM</p>
      </div>
    </section>

    <!-- CONTACT FORM -->
    <section class="max-w-4xl mx-auto px-6 py-10">
      <div class="bg-white/5 p-8 rounded-3xl card-glass shadow-lg fade-up" data-delay="200">
        <h3 class="text-2xl font-semibold text-white mb-6 text-center">Send us a message</h3>
        <form id="contactForm" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input id="c_name" type="text" placeholder="Your Name" required class="px-4 py-3 rounded-lg border border-white/10 bg-white/5 text-white placeholder-slate-400 focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
            <input id="c_email" type="email" placeholder="Your Email" required class="px-4 py-3 rounded-lg border border-white/10 bg-white/5 text-white placeholder-slate-400 focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
          </div>
          <input id="c_subject" type="text" placeholder="Subject" class="w-full px-4 py-3 rounded-lg border border-white/10 bg-white/5 text-white placeholder-slate-400 focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
          <textarea id="c_message" rows="5" placeholder="Your message..." required class="w-full px-4 py-3 rounded-lg border border-white/10 bg-white/5 text-white placeholder-slate-400 focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none"></textarea>
          <div class="text-center">
            <button type="submit" class="bg-[color:var(--accent)] px-6 py-3 rounded-lg text-white font-semibold hover:bg-blue-500 transition">Send Message</button>
          </div>
        </form>
      </div>
    </section>

    <!-- MAP SECTION -->
    <section class="max-w-6xl mx-auto px-6 pb-20">
      <div class="rounded-3xl overflow-hidden shadow-lg fade-up" data-delay="240">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d19861.24313777076!2d-0.127758!3d51.507351!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48761b334a8d6e09%3A0x9ad740d64b8ad509!2sGlobal%20Map!5e0!3m2!1sen!2s!4v1692030553810!5m2!1sen!2s" 
          width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy">
        </iframe>
      </div>
    </section>

  </main>

  <!-- FOOTER -->
  <footer class="bg-slate-900 border-t border-white/6">
    <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <h4 class="text-white font-bold">PawVerse</h4>
        <p class="text-slate-300 mt-2">Trusted pet products & veterinary services.</p>
      </div>

      <div>
        <h5 class="text-slate-200 font-semibold mb-2">Explore</h5>
        <ul class="text-slate-300 space-y-1">
          <li><a href="products.php">Shop</a></li>
          <li><a href="services.php">Services</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>

      <div>
        <h5 class="text-slate-200 font-semibold mb-2">Contact</h5>
        <p class="text-slate-300 text-sm">support@pawverse.com<br/>+880 1XXX-XXXXXX</p>
      </div>
    </div>
    <div class="text-center text-slate-500 text-sm py-6">© <?php echo date('Y'); ?> PawVerse. All rights reserved.</div>
  </footer>

  <script>
    // mobile toggle
    document.getElementById('mobileToggle').addEventListener('click', () => {
      document.getElementById('mobileMenu').classList.toggle('hidden');
    });

    // fade-in observer
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if(entry.isIntersecting) entry.target.classList.add('in-view');
      });
    }, { threshold: 0.12 });

    document.querySelectorAll('.fade-up').forEach((el, idx) => {
      const delay = el.dataset.delay ? Number(el.dataset.delay) : (idx * 40);
      el.style.transitionDelay = `${delay}ms`;
      observer.observe(el);
    });

    // contact form demo
    document.getElementById('contactForm').addEventListener('submit', e => {
      e.preventDefault();
      const name = document.getElementById('c_name').value.trim();
      const email = document.getElementById('c_email').value.trim();
      const message = document.getElementById('c_message').value.trim();

      if (!name || !email || !message) {
        alert('Please fill in all required fields.');
        return;
      }

      // Demo success popup
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
