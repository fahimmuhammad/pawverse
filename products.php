<?php
session_start();
include(__DIR__ . '/config/db.php');

// Check login
$isLoggedIn = isset($_SESSION['user_id']);
$username = '';

if ($isLoggedIn) {
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();
}

// Fetch products dynamically (based on your actual table)
$products = [];
$query = "SELECT id, name, category, description, price, stock, image FROM products ORDER BY created_at DESC";
if ($result = $conn->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    $result->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawVerse — Products</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <style>
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
      --panel: rgba(255,255,255,0.04);
      --soft: rgba(255,255,255,0.02);
      background: linear-gradient(135deg, var(--deep-1), var(--deep-2));
      color: var(--text);
    }

    body {
      background: var(--deep-1);
      color: var(--text);
      font-family: "Inter", sans-serif;
    }

    .switch {
      width: 52px;
      height: 30px;
      border-radius: 999px;
      padding: 3px;
      display: inline-flex;
      align-items: center;
      cursor: pointer;
      background: rgba(0, 0, 0, 0.06);
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
    .switch.on {
      background: linear-gradient(90deg, var(--accent), #60a5fa);
    }
    .switch.on .knob {
      transform: translateX(22px);
    }

    .product-card {
      transition: transform .25s, box-shadow .25s;
    }
    .product-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 20px 40px rgba(10,15,30,0.25);
    }
  </style>
</head>

<body class="antialiased">

  <!-- NAV -->
  <header class="fixed w-full z-40 bg-[color:var(--panel)] bg-opacity-70 backdrop-blur-md shadow-sm">
    <div class="max-w-7xl mx-auto px-6">
      <nav class="flex items-center justify-between py-4">
        <a href="index.php" class="flex items-center gap-3">
          <img src="assets/images/logo.png" alt="PawVerse Logo" class="w-10 h-10 rounded-lg">
          <h1 class="text-xl font-bold text-[color:var(--accent)]">PawVerse</h1>
        </a>

        <div class="hidden md:flex items-center gap-6">
          <a href="index.php" class="text-[color:var(--text)] hover:text-[color:var(--accent)]">Home</a>
          <a href="products.php" class="font-semibold text-[color:var(--accent)]">Products</a>
          <a href="services.php" class="text-[color:var(--text)] hover:text-[color:var(--accent)]">Services</a>
          <a href="about.php" class="text-[color:var(--text)] hover:text-[color:var(--accent)]">About</a>
          <a href="contact.php" class="text-[color:var(--text)] hover:text-[color:var(--accent)]">Contact</a>

          <?php if ($isLoggedIn): ?>
            <span class="text-sm text-[color:var(--text)] mr-2">👋 Hi, <?php echo htmlspecialchars($username); ?></span>
            <a href="auth/logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Logout</a>
          <?php else: ?>
            <a href="auth/login.php" class="bg-[color:var(--accent)] text-white px-4 py-2 rounded-lg hover:opacity-90 transition">Login</a>
          <?php endif; ?>

          <div class="flex items-center gap-2 ml-4">
            <div class="text-xs muted">☀️</div>
            <div id="themeSwitch" class="switch" title="Toggle dark mode"><div class="knob"></div></div>
            <div class="text-xs muted">🌙</div>
          </div>
        </div>
      </nav>
    </div>
  </header>

  <main class="pt-28">

    <!-- HERO -->
    <section class="text-center py-16">
      <h2 class="text-4xl font-extrabold mb-3">Shop Premium Pet Products 🐾</h2>
      <p class="text-[color:var(--text)] max-w-2xl mx-auto">Carefully curated supplies to keep your furry, feathery, or scaly friends happy and healthy.</p>
    </section>

    <!-- PRODUCT GRID -->
    <section class="max-w-7xl mx-auto px-6 pb-20">
      <h3 class="text-2xl font-semibold mb-6">All Products</h3>

      <div id="grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <?php foreach ($products as $p): ?>
        <article class="rounded-2xl bg-[color:var(--panel)] p-4 product-card shadow border border-white/5" data-category="<?php echo htmlspecialchars($p['category']); ?>">
          <div class="relative">
            <div class="aspect-[4/3] rounded-lg overflow-hidden bg-white/4 flex items-center justify-center">
              <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" class="w-40 object-contain">
            </div>
          </div>
          <div class="mt-4">
            <h4 class="font-semibold text-lg"><?php echo htmlspecialchars($p['name']); ?></h4>
            <p class="text-sm text-gray-500 mt-2"><?php echo htmlspecialchars($p['description']); ?></p>
          </div>
          <div class="mt-4 flex items-center justify-between">
            <div class="font-bold text-[color:var(--accent)]">$<?php echo number_format($p['price'], 2); ?></div>
            <button class="bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white hover:opacity-90">Add to cart</button>
          </div>
          <div class="text-xs text-gray-400 mt-2">Stock: <?php echo (int)$p['stock']; ?></div>
        </article>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

  <footer class="bg-[color:var(--panel)] border-t border-white/5 text-center text-sm py-6">
    © <?php echo date("Y"); ?> PawVerse. All rights reserved.
  </footer>

  <script src="assets/js/theme.js"></script>
</body>
</html>
