<?php
session_start();

/* -------------------------------
   CART INIT
-------------------------------- */
$cart = $_SESSION['cart'] ?? [];

/* -------------------------------
   UPDATE QUANTITY
-------------------------------- */
if (isset($_POST['update_qty'])) {
    $id = (int)$_POST['product_id'];
    $action = $_POST['action'];

    if (isset($cart[$id])) {
        if ($action === 'inc') {
            $cart[$id]['qty']++;
        } elseif ($action === 'dec' && $cart[$id]['qty'] > 1) {
            $cart[$id]['qty']--;
        }
        $_SESSION['cart'] = $cart;
    }
}

/* -------------------------------
   REMOVE ITEM
-------------------------------- */
if (isset($_POST['remove'])) {
    $id = (int)$_POST['product_id'];
    unset($cart[$id]);
    $_SESSION['cart'] = $cart;
}

/* -------------------------------
   TOTAL
-------------------------------- */
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['qty'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PawVerse — Cart</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<style>
:root {
  --bg:#f8fafc;--text:#0f1724;--panel:#ffffff;--accent:#3b82f6;
}
.theme-dark {
  --bg:linear-gradient(135deg,#0f1724,#334155);
  --text:#e6eef8;--panel:rgba(255,255,255,.05);
}
body{background:var(--bg);color:var(--text);font-family:Inter,sans-serif;}
.panel{background:var(--panel);}
</style>
</head>

<body>

<?php include 'includes/header.php'; ?>

<main class="max-w-6xl mx-auto px-6 py-16">

<h1 class="text-3xl font-bold mb-8">Your Cart 🛒</h1>

<?php if (empty($cart)): ?>
<p>Your cart is empty.</p>
<a href="products.php" class="text-blue-600 font-semibold">Continue Shopping →</a>

<?php else: ?>

<div class="grid md:grid-cols-3 gap-8">

<!-- CART ITEMS -->
<section class="md:col-span-2 space-y-4">
<?php foreach ($cart as $item): ?>
<div class="panel p-4 rounded-xl flex justify-between items-center">

<div>
<h3 class="font-semibold"><?php echo htmlspecialchars($item['name']); ?></h3>
<p>$<?php echo number_format($item['price'],2); ?></p>

<form method="POST" class="flex items-center gap-2 mt-2">
<input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
<button name="update_qty" value="1" type="submit" name="action" value="dec"
        onclick="this.form.action.value='dec'"
        class="px-2 border rounded">−</button>

<span><?php echo $item['qty']; ?></span>

<button name="update_qty" value="1" type="submit" name="action" value="inc"
        onclick="this.form.action.value='inc'"
        class="px-2 border rounded">+</button>
</form>
</div>

<div class="text-right">
<p class="font-semibold">
$<?php echo number_format($item['price'] * $item['qty'], 2); ?>
</p>

<form method="POST">
<input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
<button name="remove" class="text-red-500 text-sm mt-2">Remove</button>
</form>
</div>

</div>
<?php endforeach; ?>
</section>

<!-- SUMMARY -->
<aside class="panel p-6 rounded-xl">
<h3 class="text-xl font-semibold mb-4">Order Summary</h3>

<p class="flex justify-between mb-2">
<span>Total</span>
<strong>$<?php echo number_format($total,2); ?></strong>
</p>

<a href="checkout.php"
class="block text-center bg-[color:var(--accent)] text-white py-3 rounded-lg font-semibold mt-4">
Proceed to Checkout
</a>

<a href="products.php"
class="block text-center border mt-3 py-2 rounded-lg">
Continue Shopping
</a>
</aside>

</div>
<?php endif; ?>

</main>

<script src="assets/js/theme.js"></script>
</body>
</html>
