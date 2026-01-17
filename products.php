<?php
session_start();
require_once __DIR__ . '/config/db.php';

/* ===============================
   CART INIT (STANDARD)
================================ */
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ===============================
   ADD TO CART (NO REDIRECT)
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {

    $productId = (int) $_POST['product_id'];

    $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id=?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($product) {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['qty']++;
        } else {
            $_SESSION['cart'][$productId] = [
                'id'    => $product['id'],
                'name'  => $product['name'],
                'price' => (float)$product['price'],
                'image' => $product['image'],
                'qty'   => 1
            ];
        }
    }

    // Prevent form re-submit
    header("Location: products.php?added=1");
    exit;
}

/* ===============================
   FETCH PRODUCTS
================================ */
$products = [];
$res = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PawVerse — Products</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="https://cdn.tailwindcss.com"></script>
<link rel="icon" href="assets/images/logo.png">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
  --bg:#f8fafc; --text:#0f1724; --muted:#64748b;
  --panel:#ffffff; --accent:#3b82f6;
}
.theme-dark {
  --bg:linear-gradient(135deg,#0f1724,#334155);
  --text:#e6eef8; --muted:#94a3b8;
  --panel:rgba(255,255,255,.05);
}
body {
  background:var(--bg);
  color:var(--text);
  font-family:"Inter",sans-serif;
  transition:.3s;
}
.panel { background:var(--panel); }
.muted { color:var(--muted); }
</style>
</head>

<body>

<!-- HEADER -->
<?php include 'includes/header.php'; ?>

<!-- HERO -->
<section class="bg-[color:var(--panel)] py-16">
  <div class="max-w-7xl mx-auto px-6 text-center">
    <h1 class="text-4xl font-extrabold mb-4">Shop Premium Pet Products 🐾</h1>
    <p class="muted text-lg max-w-3xl mx-auto">
      Carefully curated supplies to keep your furry friends happy.
    </p>
  </div>
</section>

<!-- SUCCESS NOTICE -->
<?php if (isset($_GET['added'])): ?>
<div class="max-w-7xl mx-auto px-6 mt-6">
  <div class="bg-green-600 text-white px-4 py-3 rounded-lg shadow">
    ✅ Product added to cart.
    <a href="cart.php" class="underline font-semibold ml-2">Go to Cart</a>
  </div>
</div>
<?php endif; ?>

<!-- PRODUCTS -->
<section class="max-w-7xl mx-auto px-6 py-16">
<h2 class="text-3xl font-bold mb-10 text-center">Our Products</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">

<?php if ($products): foreach ($products as $p): ?>
  <div class="panel rounded-xl shadow p-4 hover:shadow-lg transition">

    <img src="<?php echo htmlspecialchars($p['image']); ?>"
         alt="<?php echo htmlspecialchars($p['name']); ?>"
         class="w-full h-48 object-cover rounded mb-4">

    <h3 class="font-semibold text-lg mb-1">
      <?php echo htmlspecialchars($p['name']); ?>
    </h3>

    <p class="muted text-sm mb-3">
      <?php echo htmlspecialchars($p['description']); ?>
    </p>

    <div class="flex justify-between items-center">
      <span class="font-bold text-[color:var(--accent)]">
        $<?php echo number_format($p['price'],2); ?>
      </span>

      <!-- ADD TO CART -->
      <form method="POST">
        <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
        <button type="submit"
                name="add_to_cart"
                class="bg-[color:var(--accent)] text-white px-4 py-2 rounded-lg text-sm hover:opacity-90 transition">
          Add to Cart
        </button>
      </form>
    </div>
  </div>
<?php endforeach; else: ?>
  <p class="col-span-full text-center muted">No products available.</p>
<?php endif; ?>

</div>
</section>

<footer class="bg-slate-900 text-slate-300 py-6 text-center">
© <?php echo date('Y'); ?> PawVerse. All rights reserved.
</footer>

<script src="assets/js/theme.js"></script>
</body>
</html>
