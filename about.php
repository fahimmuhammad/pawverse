<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawVerse — About</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="assets/images/logo.png">

  <style>
    :root {
      /* Light mode */
      --bg: #f8fafc;
      --text: #0f1724;
      --muted: #475569;
      --panel: #ffffff;
      --accent: #3b82f6;
      --footer-bg: #0f1724;
      --footer-text: #cbd5e1;
    }

    .theme-dark {
      /* Dark mode */
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

    .panel {
      background: var(--panel);
      transition: background-color 0.4s, color 0.4s;
    }

    .muted { color: var(--muted); }
    .accent { color: var(--accent); }

    /* Glass-style cards */
    .card-glass {
      background: var(--panel);
      border: 1px solid rgba(255,255,255,0.08);
      backdrop-filter: blur(8px);
      transition: all 0.4s ease;
    }

    /* slider switch */
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
  <header class="fixed w-full z-40 panel shadow border-b border-white/10">
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
          <a href="about.php" class="font-semibold text-[color:var(--accent)]">About</a>
          <a href="contact.php" class="muted hover:text-[color:var(--accent)]">Contact</a>
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
        <a href="about.php" class="text-[color:var(--accent)] font-semibold">About</a>
        <a href="contact.php" class="muted">Contact</a>
        <a href="auth/login.php" class="mt-2 inline-block bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white text-center">Log in</a>
      </div>
    </div>
  </header>

  <main class="pt-28">

    <!-- HERO -->
    <section class="relative overflow-hidden">
      <div class="absolute inset-0 -z-10 bg-[color:var(--panel)] opacity-70"></div>
      <div class="max-w-7xl mx-auto px-6 py-20 flex flex-col lg:flex-row items-center gap-10">
        <div class="lg:w-6/12">
          <p class="text-sm accent font-semibold mb-3">Built with love</p>
          <h2 class="text-4xl md:text-5xl font-extrabold leading-tight tracking-tight mb-4">Our Story & Mission</h2>
          <p class="muted max-w-xl mb-6">PawVerse started as a small idea: to bring quality pet products and trusted veterinary care into one thoughtful place. We believe pet care should be accessible, compassionate, and reliable.</p>
          <a href="products.php" class="inline-flex items-center gap-3 bg-[color:var(--accent)] text-white px-5 py-3 rounded-lg shadow hover:scale-[1.01] transition">Explore the Shop</a>
        </div>

        <div class="lg:w-1/2 flex justify-center hero-parallax" id="heroParallax">
          <figure class="w-[520px] max-w-full rounded-3xl overflow-hidden shadow-2xl card-glass ring-1 ring-white/6">
            <img src="assets/images/hero-pet.png" alt="Happy pet" class="w-full h-[420px] object-cover">
          </figure>
        </div>
      </div>
    </section>

    <!-- MISSION & VALUES -->
    <section class="max-w-7xl mx-auto px-6 py-12">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <div>
          <h3 class="text-2xl font-semibold mb-4">Mission</h3>
          <p class="muted mb-6">To support pet owners with high-quality products and easy access to trusted veterinary care — fostering healthier, happier lives for pets and the people who love them.</p>

          <h3 class="text-2xl font-semibold mb-4">Core Values</h3>
          <ul class="muted space-y-2">
            <li>• Quality & Safety: We curate safe, tested products.</li>
            <li>• Compassion: Care-first approach in every service.</li>
            <li>• Accessibility: Simple, affordable access to vets & supplies.</li>
          </ul>
        </div>

        <div>
          <div class="rounded-2xl p-6 card-glass">
            <h4 class="font-semibold mb-3">By the numbers</h4>
            <div class="grid grid-cols-3 gap-4 text-center">
              <div><div class="text-2xl font-bold accent">1.2k+</div><p class="muted text-sm">Orders</p></div>
              <div><div class="text-2xl font-bold accent">45</div><p class="muted text-sm">Partner Vets</p></div>
              <div><div class="text-2xl font-bold accent">98%</div><p class="muted text-sm">Satisfaction</p></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- TEAM -->
    <section class="max-w-7xl mx-auto px-6 py-12">
      <h3 class="text-2xl font-semibold mb-6">Meet the Team</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php
        $team = [
          ["name"=>"Aisha Rahman","role"=>"Founder & CEO","img"=>"team1.png"],
          ["name"=>"Mizan Ahmed","role"=>"Head of Product","img"=>"team2.png"],
          ["name"=>"Dr. Lina Chowdhury","role"=>"Lead Veterinarian","img"=>"team3.png"],
          ["name"=>"Tanvin Amin","role"=>"Design & Dev","img"=>"team4.png"]
        ];
        foreach($team as $member): ?>
        <article class="rounded-2xl p-5 card-glass text-center">
          <div class="aspect-square rounded-xl overflow-hidden bg-[color:var(--panel)] flex items-center justify-center">
            <img src="assets/images/<?php echo $member['img']; ?>" alt="<?php echo $member['name']; ?>" class="w-28">
          </div>
          <div class="mt-4">
            <h4 class="font-semibold"><?php echo $member['name']; ?></h4>
            <p class="muted text-sm"><?php echo $member['role']; ?></p>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- TIMELINE -->
    <section class="max-w-7xl mx-auto px-6 py-12">
      <h3 class="text-2xl font-semibold mb-6">Project Approach</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="rounded-2xl p-6 card-glass"><h4 class="font-semibold mb-2">Plan</h4><p class="muted text-sm">Design & requirement gathering, 2 weeks.</p></div>
        <div class="rounded-2xl p-6 card-glass"><h4 class="font-semibold mb-2">Build</h4><p class="muted text-sm">Frontend & backend implementation, 6–7 weeks.</p></div>
        <div class="rounded-2xl p-6 card-glass"><h4 class="font-semibold mb-2">Deliver</h4><p class="muted text-sm">Testing, documentation, and final submission, 2–3 weeks.</p></div>
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

  <!-- Theme & Scripts -->
  <script src="assets/js/theme.js"></script>
  <script>
    // mobile toggle
    document.getElementById('mobileToggle').addEventListener('click', () => {
      document.getElementById('mobileMenu').classList.toggle('hidden');
    });

    // hero parallax
    const heroParallax = document.getElementById('heroParallax');
    window.addEventListener('scroll', () => {
      const scrolled = window.scrollY;
      if (heroParallax) heroParallax.style.transform = `translateY(${Math.min(scrolled * 0.06, 36)}px)`;
    });

    // fade-in
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('in-view'); });
    }, { threshold: 0.12 });
    document.querySelectorAll('.fade-up').forEach((el, idx) => {
      el.style.transitionDelay = `${idx * 60}ms`;
      observer.observe(el);
    });
  </script>
</body>
</html>
