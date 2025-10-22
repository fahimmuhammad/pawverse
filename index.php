<?php include('includes/db_connect.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PawVerse | Home</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="assets/images/logo.png">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      /* Light Mode */
      --bg: #f8fafc;
      --text: #0f1724;
      --muted: #64748b;
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

    .panel {
      background: var(--panel);
      transition: background-color 0.4s, color 0.4s;
    }

    .muted { color: var(--muted); }

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
  </style>
</head>

<body>

  <!-- Navbar -->
  <header class="panel shadow-sm sticky top-0 z-50 border-b border-white/10">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-extrabold text-[color:var(--accent)]">PawVerse</h1>

      <nav class="hidden md:flex space-x-6 items-center">
        <a href="index.php" class="font-semibold text-[color:var(--accent)]">Home</a>
        <a href="products.php" class="muted hover:text-[color:var(--accent)] transition">Products</a>
        <a href="services.php" class="muted hover:text-[color:var(--accent)] transition">Services</a>
        <a href="about.php" class="muted hover:text-[color:var(--accent)] transition">About</a>
        <a href="contact.php" class="muted hover:text-[color:var(--accent)] transition">Contact</a>
        <a href="auth/login.php" class="bg-[color:var(--accent)] text-white px-4 py-2 rounded-lg hover:opacity-90 transition">Login</a>

        <!-- Theme Toggle -->
        <div class="flex items-center gap-2 ml-4">
          <div class="text-xs muted">☀️</div>
          <div id="themeSwitch" role="switch" aria-checked="false" tabindex="0" class="switch" title="Toggle dark mode">
            <div class="knob"></div>
          </div>
          <div class="text-xs muted">🌙</div>
        </div>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="relative bg-[color:var(--panel)]">
    <div class="max-w-7xl mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-10">
      <div class="flex-1">
        <h2 class="text-4xl md:text-5xl font-extrabold mb-4">
          Care. Comfort. <span class="text-[color:var(--accent)]">PawVerse.</span>
        </h2>
        <p class="muted text-lg mb-6">
          Discover everything your furry friend deserves – from premium products to professional veterinary care, all in one place.
        </p>
        <a href="products.php" class="bg-[color:var(--accent)] text-white px-6 py-3 rounded-lg text-lg font-semibold hover:opacity-90 transition">Shop Now</a>
        <a href="services.php" class="ml-4 text-[color:var(--accent)] font-semibold hover:underline">Book a Vet</a>
      </div>
      <div class="flex-1">
        <img src="assets/images/hero-pet.png" alt="PawVerse Pet" class="rounded-3xl shadow-xl w-full">
      </div>
    </div>
  </section>

  <!-- Categories Section -->
  <section class="max-w-7xl mx-auto px-6 py-20">
    <h3 class="text-3xl font-bold text-center mb-10">Shop by Category</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
      <div class="panel shadow-md rounded-xl p-6 text-center hover:shadow-lg transition">
        <img src="assets/images/Dog_Shop_by_category.png" class="w-24 mx-auto mb-4">
        <h4 class="font-semibold text-lg">Dogs</h4>
      </div>
      <div class="panel shadow-md rounded-xl p-6 text-center hover:shadow-lg transition">
        <img src="assets/images/Cat_Shop_by_category.png" class="w-24 mx-auto mb-4">
        <h4 class="font-semibold text-lg">Cats</h4>
      </div>
      <div class="panel shadow-md rounded-xl p-6 text-center hover:shadow-lg transition">
        <img src="assets/images/Bird_Shop_by_category.png" class="w-24 mx-auto mb-4">
        <h4 class="font-semibold text-lg">Birds</h4>
      </div>
      <div class="panel shadow-md rounded-xl p-6 text-center hover:shadow-lg transition">
        <img src="assets/images/Fish_Shop_by_category.png" class="w-24 mx-auto mb-4">
        <h4 class="font-semibold text-lg">Fish</h4>
      </div>
    </div>
  </section>

  <!-- Vet Services Section -->
  <section class="py-20 bg-[color:var(--panel)]">
    <div class="max-w-7xl mx-auto px-6 text-center">
      <h3 class="text-3xl font-bold mb-6">Veterinary Care You Can Trust</h3>
      <p class="muted max-w-3xl mx-auto mb-10">
        Schedule appointments with verified veterinarians to ensure your pets stay healthy, happy, and cared for by trusted professionals.
      </p>
      <a href="services.php" class="bg-[color:var(--accent)] text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition">Book Appointment</a>
    </div>
  </section>

  <!-- Footer -->
  <footer class="pt-10 pb-6" style="background: var(--footer-bg); color: var(--footer-text);">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-10">
      <div>
        <h4 class="text-xl font-bold text-white mb-3">PawVerse</h4>
        <p>Your one-stop pet care platform for love, health, and happiness.</p>
      </div>
      <div>
        <h4 class="text-lg font-semibold text-white mb-3">Quick Links</h4>
        <ul class="space-y-2">
          <li><a href="about.php" class="hover:text-white">About</a></li>
          <li><a href="products.php" class="hover:text-white">Shop</a></li>
          <li><a href="services.php" class="hover:text-white">Services</a></li>
          <li><a href="contact.php" class="hover:text-white">Contact</a></li>
        </ul>
      </div>
      <div>
        <h4 class="text-lg font-semibold text-white mb-3">Contact Us</h4>
        <p>Email: support@pawverse.com</p>
        <p>Phone: +880 1XXX-XXXXXX</p>
      </div>
    </div>
    <div class="text-center text-sm mt-8 border-t border-white/10 pt-4">
      © <?php echo date("Y"); ?> PawVerse. All rights reserved.
    </div>
  </footer>

  <!-- Global Theme Script -->
  <script src="assets/js/theme.js"></script>

</body>
</html>
