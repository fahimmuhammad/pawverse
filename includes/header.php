<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['user_name'] ?? 'User';

/* CART COUNT */
$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += (int)$item['qty'];
    }
}
?>

<header class="panel shadow-sm sticky top-0 z-50 border-b border-white/10">
  <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

    <!-- LOGO -->
    <a href="index.php" class="flex items-center gap-3">
      <img src="assets/images/pawverse_logo.png" class="w-10 h-10 rounded-lg" alt="PawVerse">
      <h1 class="text-2xl font-extrabold text-[color:var(--accent)]">PawVerse</h1>
    </a>

    <!-- NAV (DESKTOP) -->
    <nav class="hidden md:flex items-center gap-6">

      <a href="index.php">Home</a>
      <a href="products.php">Products</a>
      <a href="services.php">Services</a>
      <a href="about.php">About</a>
      <a href="contact.php">Contact</a>

      <?php if ($isLoggedIn): ?>
        <!-- ✅ THIS WAS MISSING FOR YOU -->
        <a href="services.php#myAppointments"
           class="hover:text-[color:var(--accent)]">
          My Appointments
        </a>

        <a href="my-orders.php"
           class="hover:text-[color:var(--accent)]">
          My Orders
        </a>
      <?php endif; ?>

      <?php if ($isLoggedIn): ?>
        <span class="text-sm">👋 <?php echo htmlspecialchars($username); ?></span>
        <a href="auth/logout.php"
           class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
          Logout
        </a>
      <?php else: ?>
        <a href="auth/login.php"
           class="bg-[color:var(--accent)] text-white px-4 py-2 rounded-lg">
          Login
        </a>
      <?php endif; ?>

      <!-- CART -->
      <a href="cart.php" class="relative ml-2">
        🛒
        <?php if ($cartCount > 0): ?>
          <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs
                       w-5 h-5 flex items-center justify-center rounded-full">
            <?php echo $cartCount; ?>
          </span>
        <?php endif; ?>
      </a>

      <!-- THEME TOGGLE -->
      <div class="flex items-center gap-2 ml-3">
        <span class="text-xs">☀️</span>
        <div id="themeSwitch" class="switch cursor-pointer">
          <div class="knob"></div>
        </div>
        <span class="text-xs">🌙</span>
      </div>

    </nav>

  </div>
</header>
