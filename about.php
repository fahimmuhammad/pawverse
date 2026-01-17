<?php
session_start();
?>
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
      --bg: #f8fafc;
      --text: #0f1724;
      --muted: #475569;
      --panel: #ffffff;
      --accent: #3b82f6;
      --footer-bg: #0f1724;
      --footer-text: #cbd5e1;
    }

    .theme-dark {
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

    .panel { background: var(--panel); }
    .muted { color: var(--muted); }
    .accent { color: var(--accent); }

    .card-glass {
      background: var(--panel);
      border: 1px solid rgba(255,255,255,0.08);
      backdrop-filter: blur(8px);
      transition: all 0.4s ease;
    }

    .fade-up {
      transform: translateY(18px);
      opacity: 0;
      transition: all .6s cubic-bezier(.16,.84,.24,1);
    }
    .fade-up.in-view {
      transform: translateY(0);
      opacity: 1;
    }
  </style>
</head>

<body>

<!-- ✅ UNIVERSAL HEADER (ONLY CHANGE MADE) -->
<?php include('includes/header.php'); ?>

<main class="pt-28">

  <!-- HERO -->
  <section class="relative overflow-hidden">
    <div class="absolute inset-0 -z-10 bg-[color:var(--panel)] opacity-70"></div>
    <div class="max-w-7xl mx-auto px-6 py-20 flex flex-col lg:flex-row items-center gap-10">
      <div class="lg:w-6/12">
        <p class="text-sm accent font-semibold mb-3">Built with love</p>
        <h2 class="text-4xl md:text-5xl font-extrabold leading-tight tracking-tight mb-4">
          Our Story & Mission
        </h2>
        <p class="muted max-w-xl mb-6">
          PawVerse started as a small idea: to bring quality pet products and trusted veterinary care into one thoughtful place.
        </p>
        <a href="products.php"
           class="inline-flex items-center gap-3 bg-[color:var(--accent)] text-white px-5 py-3 rounded-lg shadow">
          Explore the Shop
        </a>
      </div>

      <div class="lg:w-1/2 flex justify-center hero-parallax" id="heroParallax">
        <figure class="w-[620px] max-w-full rounded-3xl overflow-hidden shadow-2xl card-glass ring-1 ring-white/6">
          <img src="assets/images/banner.png" alt="Happy pet"
               class="w-full h-[420px] object-cover">
        </figure>
      </div>
    </div>
  </section>

  <!-- MISSION & VALUES -->
  <section class="max-w-7xl mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
      <div>
        <h3 class="text-2xl font-semibold mb-4">Mission</h3>
        <p class="muted mb-6">
          To support pet owners with high-quality products and easy access to trusted veterinary care.
        </p>

        <h3 class="text-2xl font-semibold mb-4">Core Values</h3>
        <ul class="muted space-y-2">
          <li>• Quality & Safety</li>
          <li>• Compassion</li>
          <li>• Accessibility</li>
        </ul>
      </div>

      <div class="rounded-2xl p-6 card-glass">
        <h4 class="font-semibold mb-3">By the numbers</h4>
        <div class="grid grid-cols-3 gap-4 text-center">
          <div><div class="text-2xl font-bold accent">1.2k+</div><p class="muted text-sm">Orders</p></div>
          <div><div class="text-2xl font-bold accent">45</div><p class="muted text-sm">Vets</p></div>
          <div><div class="text-2xl font-bold accent">98%</div><p class="muted text-sm">Satisfaction</p></div>
        </div>
      </div>
    </div>
  </section>

</main>

<!-- FOOTER (UNCHANGED) -->
<footer class="pt-10 pb-6" style="background: var(--footer-bg); color: var(--footer-text);">
  <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>
      <h4 class="text-xl font-bold text-white mb-3">PawVerse</h4>
      <p>Trusted pet products & veterinary services.</p>
    </div>
    <div>
      <h5 class="font-semibold mb-2">Explore</h5>
      <ul class="space-y-1 text-sm">
        <li><a href="products.php">Shop</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </div>
    <div>
      <h5 class="font-semibold mb-2">Contact</h5>
      <p class="text-sm">support@pawverse.com<br>+880 1XXX-XXXXXX</p>
    </div>
  </div>
  <div class="text-center text-sm mt-8 border-t border-white/10 pt-4">
    © <?php echo date('Y'); ?> PawVerse. All rights reserved.
  </div>
</footer>

<script src="assets/js/theme.js"></script>
<script>
  const heroParallax = document.getElementById('heroParallax');
  window.addEventListener('scroll', () => {
    if (heroParallax)
      heroParallax.style.transform = `translateY(${Math.min(window.scrollY * 0.06, 36)}px)`;
  });
</script>

</body>
</html>
