<?php
session_start();

$cart = $_SESSION['cart'] ?? [];

/* -------------------------------
   UPDATE QUANTITY / REMOVE
-------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['update_qty'])) {
        $id = (int) $_POST['product_id'];
        $qty = max(1, (int) $_POST['qty']);
        if (isset($cart[$id])) {
            $cart[$id]['qty'] = $qty;
        }
    }

    if (isset($_POST['remove_item'])) {
        $id = (int) $_POST['product_id'];
        unset($cart[$id]);
    }

    $_SESSION['cart'] = $cart;
    header("Location: cart.php");
    exit;
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
<link rel="icon" href="assets/images/logo.png">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
  --bg:#f8fafc;
  --text:#0f1724;
  --panel:#ffffff;
  --accent:#3b82f6;
}
.theme-dark {
  --bg:linear-gradient(135deg,#0f1724,#334155);
  --text:#e6eef8;
  --panel:rgba(255,255,255,.05);
}
body { background:var(--bg); color:var(--text); font-family:"Inter",sans-serif; }
.panel { background:var(--panel); }
</style>
</head>

<body>

<?php include 'includes/header.php'; ?>

<main class="max-w-5xl mx-auto px-6 py-20">

<h1 class="text-3xl font-bold mb-8">Your Cart</h1>

<?php if (empty($cart)): ?>
  <p class="opacity-70">Your cart is empty.</p>
  <a href="products.php" class="text-blue-600 underline mt-4 inline-block">Continue Shopping</a>
<?php else: ?>

<div class="space-y-4 mb-10">
<?php foreach ($cart as $item): ?>
  <div class="panel p-5 rounded-xl flex justify-between items-center">

    <div>
      <h3 class="font-semibold"><?php echo htmlspecialchars($item['name']); ?></h3>
      <p class="text-sm opacity-70">$<?php echo number_format($item['price'],2); ?></p>
    </div>

    <form method="POST" class="flex items-center gap-3">
      <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">

      <input
        type="number"
        name="qty"
        value="<?php echo $item['qty']; ?>"
        min="1"
        class="w-16 border rounded px-2 py-1 text-center"
      >

      <button name="update_qty" class="text-blue-600 text-sm">Update</button>
      <button name="remove_item" class="text-red-600 text-sm">Remove</button>
    </form>

    <strong>
      $<?php echo number_format($item['price'] * $item['qty'], 2); ?>
    </strong>

  </div>
<?php endforeach; ?>
</div>

<div class="panel p-6 rounded-xl flex justify-between items-center">
  <span class="text-xl font-bold">Total:</span>
  <span class="text-xl font-bold">$<?php echo number_format($total,2); ?></span>
</div>

<div class="mt-6 flex justify-between">
  <a href="products.php" class="text-blue-600 underline">Continue Shopping</a>
  <a href="checkout.php"
     class="bg-[color:var(--accent)] text-white px-6 py-3 rounded-lg font-semibold">
     Proceed to Checkout
  </a>
</div>

<?php endif; ?>

</main>

<script src="assets/js/theme.js"></script>
</body>
</html>
