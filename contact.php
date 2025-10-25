<?php
session_start();
require_once __DIR__ . '/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $subject = trim($_POST['subject'] ?? '');
  $message = trim($_POST['message'] ?? '');
  $user_id = $_SESSION['user_id'] ?? null;

  if ($name && $email && $message) {
    $stmt = $conn->prepare("
      INSERT INTO messages (user_id, sender_name, sender_email, subject, message, status)
      VALUES (?, ?, ?, ?, ?, 'unread')
    ");
    $stmt->bind_param("issss", $user_id, $name, $email, $subject, $message);
    $stmt->execute();
    $stmt->close();
    $success = true;
  } else {
    $error = "Please fill in all required fields.";
  }
}
?>
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
      --bg: #f8fafc; --text: #0f1724; --muted: #475569; --panel: #ffffff; --accent: #3b82f6;
      --footer-bg: #0f1724; --footer-text: #cbd5e1;
    }
    .theme-dark {
      --bg: linear-gradient(135deg, #0f1724, #334155); --text: #e6eef8; --muted: #94a3b8;
      --panel: rgba(255, 255, 255, 0.05); --accent: #3b82f6; --footer-bg: #0b1220; --footer-text: #94a3b8;
    }
    body { background: var(--bg); color: var(--text); font-family: "Inter", sans-serif; transition: .3s; }
    .panel { background: var(--panel); transition: .3s; }
    .muted { color: var(--muted); }
    .card-glass { background: var(--panel); border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(8px); }
    .switch { width: 52px; height: 30px; border-radius: 999px; padding: 3px; display: inline-flex; align-items: center; cursor: pointer; background: rgba(0, 0, 0, 0.06); transition: background 0.25s; }
    .switch .knob { width: 24px; height: 24px; border-radius: 999px; background: white; transform: translateX(0); transition: transform .25s; }
    .switch.on { background: linear-gradient(90deg, var(--accent), #60a5fa); }
    .switch.on .knob { transform: translateX(22px); }
  </style>
</head>
<body>

<!-- HEADER -->
<header class="fixed w-full z-40 panel border-b border-white/10 shadow">
  <div class="max-w-7xl mx-auto px-6">
    <nav class="flex items-center justify-between py-4">
      <a href="index.php" class="flex items-center gap-3">
        <img src="assets/images/logo.png" alt="PawVerse Logo" class="w-10 h-10 rounded-lg">
        <div>
          <h1 class="text-xl font-bold text-[color:var(--accent)]">PawVerse</h1>
          <p class="text-xs muted -mt-1">Care • Comfort • Community</p>
        </div>
      </a>
      <div class="hidden md:flex items-center gap-6">
        <a href="index.php" class="muted hover:text-[color:var(--accent)]">Home</a>
        <a href="products.php" class="muted hover:text-[color:var(--accent)]">Products</a>
        <a href="services.php" class="muted hover:text-[color:var(--accent)]">Services</a>
        <a href="about.php" class="muted hover:text-[color:var(--accent)]">About</a>
        <a href="contact.php" class="text-[color:var(--accent)] font-semibold">Contact</a>
        <a href="auth/login.php" class="ml-2 bg-[color:var(--accent)] text-white px-4 py-2 rounded-lg hover:opacity-90 transition">Log in</a>

        <div class="flex items-center gap-2 ml-4">
          <span class="text-xs muted">☀️</span>
          <div id="themeSwitch" class="switch"><div class="knob"></div></div>
          <span class="text-xs muted">🌙</span>
        </div>
      </div>
    </nav>
  </div>
</header>

<main class="pt-28">
  <section class="text-center py-16 bg-[color:var(--panel)]">
    <h2 class="text-4xl font-extrabold mb-4">Let’s stay in touch 🐾</h2>
    <p class="muted text-lg max-w-2xl mx-auto">We’d love to hear from you — whether it’s a question, feedback, or a hello!</p>
  </section>

  <section class="max-w-4xl mx-auto px-6 py-12">
    <div class="p-8 rounded-3xl card-glass shadow-lg">
      <?php if (!empty($success)): ?>
        <div class="bg-green-600/90 text-white text-center py-3 mb-4 rounded-lg font-semibold">Your message has been sent successfully!</div>
      <?php elseif (!empty($error)): ?>
        <div class="bg-red-500/90 text-white text-center py-3 mb-4 rounded-lg font-semibold"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <form method="POST" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input name="name" type="text" placeholder="Your Name" required class="px-4 py-3 rounded-lg border border-white/10 bg-[color:var(--panel)] text-[color:var(--text)] placeholder-[color:var(--muted)] focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
          <input name="email" type="email" placeholder="Your Email" required class="px-4 py-3 rounded-lg border border-white/10 bg-[color:var(--panel)] text-[color:var(--text)] placeholder-[color:var(--muted)] focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
        </div>
        <input name="subject" type="text" placeholder="Subject" class="w-full px-4 py-3 rounded-lg border border-white/10 bg-[color:var(--panel)] text-[color:var(--text)] placeholder-[color:var(--muted)] focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
        <textarea name="message" rows="5" placeholder="Your message..." required class="w-full px-4 py-3 rounded-lg border border-white/10 bg-[color:var(--panel)] text-[color:var(--text)] placeholder-[color:var(--muted)] focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none"></textarea>
        <div class="text-center">
          <button type="submit" class="bg-[color:var(--accent)] px-6 py-3 rounded-lg text-white font-semibold hover:opacity-90 transition">Send Message</button>
        </div>
      </form>
    </div>
  </section>
</main>

<footer class="pt-10 pb-6" style="background: var(--footer-bg); color: var(--footer-text);">
  <div class="text-center text-sm border-t border-white/10 pt-4">
    © <?php echo date('Y'); ?> PawVerse. All rights reserved.
  </div>
</footer>

<script src="assets/js/theme.js"></script>
</body>
</html>
