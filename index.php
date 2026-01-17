<?php
session_start();
include(__DIR__ . '/config/db.php');

/* -------------------------------
   Login state
-------------------------------- */
$isLoggedIn = isset($_SESSION['user_id']);
$username = '';

if ($isLoggedIn) {
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();
}

/* -------------------------------
   Categories
-------------------------------- */
$categories = [];
$res = $conn->query("SELECT name, image FROM categories LIMIT 4");

if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $categories[] = $row;
    }
} else {
    $categories = [
        ["name"=>"Dogs","image"=>"assets/images/Dog_Shop_by_category.png"],
        ["name"=>"Cats","image"=>"assets/images/Cat_Shop_by_category.png"],
        ["name"=>"Birds","image"=>"assets/images/Bird_Shop_by_category.png"],
        ["name"=>"Fish","image"=>"assets/images/Fish_Shop_by_category.png"]
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PawVerse | Home</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdn.tailwindcss.com"></script>
<link rel="icon" href="assets/images/logo.png">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
  --bg:#f8fafc; --text:#0f1724; --muted:#64748b;
  --panel:#ffffff; --accent:#3b82f6;
  --footer-bg:#0f1724; --footer-text:#cbd5e1;
}
.theme-dark {
  --bg:#0f1724; --text:#e6eef8; --muted:#94a3b8;
  --panel:#1e293b; --accent:#3b82f6;
  --footer-bg:#0b1220; --footer-text:#94a3b8;
}
body {
  background:var(--bg); color:var(--text);
  font-family:"Inter",sans-serif;
  transition:.3s;
}
.panel { background:var(--panel); }
.muted { color:var(--muted); }
.switch {
  width:52px;height:30px;border-radius:999px;padding:3px;
  display:inline-flex;align-items:center;cursor:pointer;
  background:rgba(0,0,0,.06);
}
.switch .knob {
  width:24px;height:24px;border-radius:999px;
  background:white;transition:.25s;
}
.switch.on { background:linear-gradient(90deg,var(--accent),#60a5fa); }
.switch.on .knob { transform:translateX(22px); }
</style>
</head>

<body>

<!-- ================= NAVBAR ================= -->
<?php include('includes/header.php'); ?>


<!-- ================= HERO ================= -->
<section class="bg-[color:var(--panel)] py-20">
<div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">
  <div>
    <h1 class="text-5xl font-extrabold mb-4">
      Care. Comfort. <span class="text-[color:var(--accent)]">PawVerse.</span>
    </h1>
    <p class="muted mb-6 text-lg">
      Everything your pet needs — products, vets, and love.
    </p>
    <a href="products.php"
       class="bg-[color:var(--accent)] text-white px-6 py-3 rounded-lg font-semibold">
       Shop Now
    </a>
    <a href="services.php" class="ml-4 text-[color:var(--accent)] font-semibold">
       Book a Vet
    </a>
  </div>
  <img src="assets/images/banner.png" class="rounded-3xl shadow-xl">
</div>
</section>

<!-- ================= CATEGORIES ================= -->
<section class="max-w-7xl mx-auto px-6 py-20">
<h2 class="text-3xl font-bold text-center mb-10">Shop by Category</h2>
<div class="grid grid-cols-2 md:grid-cols-4 gap-8">
<?php foreach($categories as $c): ?>
  <div class="panel rounded-xl p-6 text-center shadow">
    <img src="<?php echo $c['image']; ?>" class="w-24 mx-auto mb-4">
    <h4 class="font-semibold"><?php echo $c['name']; ?></h4>
  </div>
<?php endforeach; ?>
</div>
</section>

<!-- ================= VET CTA ================= -->
<section class="bg-[color:var(--panel)] py-20 text-center">
<h2 class="text-3xl font-bold mb-4">Veterinary Care You Can Trust</h2>
<p class="muted max-w-3xl mx-auto mb-8">
Book appointments with verified veterinarians.
</p>
<a href="services.php"
   class="bg-[color:var(--accent)] text-white px-6 py-3 rounded-lg font-semibold">
   Book Appointment
</a>
</section>

<!-- ================= FOOTER ================= -->
<footer class="pt-10 pb-6" style="background:var(--footer-bg);color:var(--footer-text);">
<div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-10">
  <div>
    <h4 class="text-white text-xl font-bold mb-2">PawVerse</h4>
    <p>Your one-stop pet care platform.</p>
  </div>
  <div>
    <h4 class="text-white font-semibold mb-2">Quick Links</h4>
    <ul class="space-y-2">
      <li><a href="about.php">About</a></li>
      <li><a href="products.php">Shop</a></li>
      <li><a href="services.php">Services</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
  </div>
  <div>
    <h4 class="text-white font-semibold mb-2">Contact</h4>
    <p>Email: support@pawverse.com</p>
    <p>Phone: +880 1XXX-XXXXXX</p>
  </div>
</div>
<div class="text-center text-sm mt-6 border-t border-white/10 pt-4">
© <?php echo date('Y'); ?> PawVerse
</div>
</footer>

<script src="assets/js/theme.js"></script>
</body>
</html>
