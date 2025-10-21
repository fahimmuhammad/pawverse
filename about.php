<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawVerse — About</title>

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
    .stat { font-size: 1.375rem; font-weight:700; color: #fff; }
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
          <a href="about.php" class="text-white font-semibold">About</a>
          <a href="contact.php" class="text-slate-200 hover:text-white">Contact</a>
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
        <a href="about.php" class="text-white font-semibold">About</a>
        <a href="contact.php" class="text-slate-200">Contact</a>
        <a href="auth/login.php" class="mt-2 inline-block bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white text-center">Log in</a>
      </div>
    </div>
  </header>

  <main class="pt-28">

    <!-- HERO -->
    <section class="relative overflow-hidden">
      <div class="absolute inset-0 -z-10">
        <div class="h-full w-full bg-gradient-to-b from-slate-900/60 to-slate-700/70"></div>
        <div class="h-full w-full opacity-30" style="background-image: radial-gradient(circle at 10% 20%, rgba(59,130,246,0.08), transparent 10%), radial-gradient(circle at 90% 80%, rgba(255,255,255,0.02), transparent 20%);"></div>
      </div>

      <div class="max-w-7xl mx-auto px-6 py-20 lg:py-28 flex flex-col lg:flex-row items-center gap-10">
        <div class="lg:w-6/12">
          <div class="fade-up" data-delay="0">
            <p class="text-sm text-amber-200 font-semibold mb-3">Built with love</p>
            <h2 class="text-4xl md:text-5xl font-extrabold leading-tight tracking-tight text-white mb-4">PawVerse — Our story & mission</h2>
            <p class="text-slate-200 max-w-xl mb-6">PawVerse started as a small idea: to bring quality pet products and trusted veterinary care into one thoughtful place. We believe pet care should be accessible, compassionate, and reliable.</p>
            <a href="products.php" class="inline-flex items-center gap-3 bg-[color:var(--accent)] text-white px-5 py-3 rounded-lg shadow hover:scale-[1.01] transition">Explore the Shop</a>
          </div>
        </div>

        <div class="lg:w-1/2 flex justify-center hero-parallax" id="heroParallax">
          <figure class="w-[520px] max-w-full rounded-3xl overflow-hidden shadow-2xl card-glass ring-1 ring-white/6">
            <img src="assets/images/hero-pet.png" alt="Happy pet with owner - PawVerse" class="w-full h-[420px] object-cover">
          </figure>
        </div>
      </div>
    </section>

    <!-- MISSION & VALUES -->
    <section class="max-w-7xl mx-auto px-6 py-12">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <div class="fade-up" data-delay="120">
          <h3 class="text-2xl font-semibold text-white mb-4">Mission</h3>
          <p class="text-slate-300 mb-6">To support pet owners with high-quality products and easy access to trusted veterinary care — fostering healthier, happier lives for pets and the people who love them.</p>

          <h3 class="text-2xl font-semibold text-white mb-4">Core Values</h3>
          <ul class="text-slate-300 space-y-2">
            <li>• Quality & Safety: We curate safe, tested products.</li>
            <li>• Compassion: Care-first approach in every service.</li>
            <li>• Accessibility: Simple, affordable access to vets & supplies.</li>
          </ul>
        </div>

        <div class="fade-up" data-delay="160">
          <div class="rounded-2xl bg-white/5 p-6 card-glass">
            <h4 class="text-white font-semibold mb-3">By the numbers</h4>
            <div class="grid grid-cols-3 gap-4">
              <div class="text-center">
                <div class="stat">1.2k+</div>
                <div class="text-slate-300 text-sm">Orders</div>
              </div>
              <div class="text-center">
                <div class="stat">45</div>
                <div class="text-slate-300 text-sm">Partner Vets</div>
              </div>
              <div class="text-center">
                <div class="stat">98%</div>
                <div class="text-slate-300 text-sm">Satisfaction</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- TEAM -->
    <section class="max-w-7xl mx-auto px-6 py-12">
      <h3 class="text-2xl font-semibold text-white mb-6 fade-up" data-delay="200">Meet the team</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <article class="rounded-2xl bg-white/6 p-5 card-glass fade-up" data-delay="220">
          <div class="aspect-square rounded-xl overflow-hidden bg-white/4 flex items-center justify-center">
            <img src="assets/images/team1.png" alt="Team member" class="w-28 img-fit">
          </div>
          <div class="mt-4">
            <h4 class="text-white font-semibold">Aisha Rahman</h4>
            <p class="text-slate-300 text-sm">Founder & CEO</p>
          </div>
        </article>

        <article class="rounded-2xl bg-white/6 p-5 card-glass fade-up" data-delay="240">
          <div class="aspect-square rounded-xl overflow-hidden bg-white/4 flex items-center justify-center">
            <img src="assets/images/team2.png" alt="Team member" class="w-28 img-fit">
          </div>
          <div class="mt-4">
            <h4 class="text-white font-semibold">Mizan Ahmed</h4>
            <p class="text-slate-300 text-sm">Head of Product</p>
          </div>
        </article>

        <article class="rounded-2xl bg-white/6 p-5 card-glass fade-up" data-delay="260">
          <div class="aspect-square rounded-xl overflow-hidden bg-white/4 flex items-center justify-center">
            <img src="assets/images/team3.png" alt="Team member" class="w-28 img-fit">
          </div>
          <div class="mt-4">
            <h4 class="text-white font-semibold">Dr. Lina Chowdhury</h4>
            <p class="text-slate-300 text-sm">Lead Veterinarian</p>
          </div>
        </article>

        <article class="rounded-2xl bg-white/6 p-5 card-glass fade-up" data-delay="280">
          <div class="aspect-square rounded-xl overflow-hidden bg-white/4 flex items-center justify-center">
            <img src="assets/images/team4.png" alt="Team member" class="w-28 img-fit">
          </div>
          <div class="mt-4">
            <h4 class="text-white font-semibold">Tanvin Amin</h4>
            <p class="text-slate-300 text-sm">Design & Dev</p>
          </div>
        </article>
      </div>
    </section>

    <!-- TIMELINE (PROJECT METHODOLOGY PREVIEW) -->
    <section class="max-w-7xl mx-auto px-6 py-12">
      <h3 class="text-2xl font-semibold text-white mb-6 fade-up" data-delay="300">Project approach</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="rounded-2xl bg-white/6 p-6 card-glass fade-up" data-delay="320">
          <h4 class="text-white font-semibold mb-2">Plan</h4>
          <p class="text-slate-300 text-sm">Design & requirement gathering, 2 weeks.</p>
        </div>
        <div class="rounded-2xl bg-white/6 p-6 card-glass fade-up" data-delay="340">
          <h4 class="text-white font-semibold mb-2">Build</h4>
          <p class="text-slate-300 text-sm">Frontend & backend implementation, 6–7 weeks.</p>
        </div>
        <div class="rounded-2xl bg-white/6 p-6 card-glass fade-up" data-delay="360">
          <h4 class="text-white font-semibold mb-2">Deliver</h4>
          <p class="text-slate-300 text-sm">Testing, documentation, and final submission, 2–3 weeks.</p>
        </div>
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

    // hero parallax
    const heroParallax = document.getElementById('heroParallax');
    window.addEventListener('scroll', () => {
      const scrolled = window.scrollY;
      if(heroParallax) heroParallax.style.transform = `translateY(${Math.min(scrolled * 0.06, 36)}px)`;
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

    // ensure graceful fallback
    document.querySelectorAll('.fade-up').forEach(el => el.classList.add('in-view'));
  </script>
</body>
</html>
