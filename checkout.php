<?php
session_start();
require_once __DIR__ . '/config/db.php';

/* -------------------------------
   AUTH CHECK
-------------------------------- */
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    header("Location: cart.php");
    exit;
}

/* -------------------------------
   CALCULATE TOTAL
-------------------------------- */
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['qty'];
}

/* -------------------------------
   PLACE ORDER
-------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {

    $name    = trim($_POST['customer_name']);
    $phone   = trim($_POST['customer_phone']);
    $address = trim($_POST['customer_address']);

    if ($name && $phone && $address) {

        $itemsJson = json_encode(array_values($cart));

        $stmt = $conn->prepare("
            INSERT INTO orders
            (user_id, customer_name, customer_phone, customer_address, items, total_amount, status)
            VALUES (?, ?, ?, ?, ?, ?, 'pending')
        ");

        $stmt->bind_param(
            "issssd",
            $userId,
            $name,
            $phone,
            $address,
            $itemsJson,
            $total
        );

        $stmt->execute();
        $stmt->close();

        // Clear cart
        unset($_SESSION['cart']);

        header("Location: order_success.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PawVerse — Checkout</title>
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
body {
  background:var(--bg);
  color:var(--text);
  font-family:"Inter",sans-serif;
}
.panel { background:var(--panel); }
</style>
</head>

<body>

<?php include 'includes/header.php'; ?>

<main class="max-w-4xl mx-auto px-6 py-20">

  <div class="panel rounded-2xl p-8 shadow">
    <h1 class="text-3xl font-bold mb-6">Checkout</h1>

    <!-- ORDER SUMMARY -->
    <div class="mb-6 space-y-3">
      <?php foreach ($cart as $item): ?>
        <div class="flex justify-between">
          <span><?php echo htmlspecialchars($item['name']); ?> × <?php echo $item['qty']; ?></span>
          <span>$<?php echo number_format($item['price'] * $item['qty'], 2); ?></span>
        </div>
      <?php endforeach; ?>

      <hr class="my-3">
      <div class="flex justify-between font-bold text-lg">
        <span>Total</span>
        <span>$<?php echo number_format($total, 2); ?></span>
      </div>
    </div>

    <!-- CHECKOUT FORM -->
    <form method="POST" class="space-y-4">
      <input
        type="text"
        name="customer_name"
        placeholder="Full Name"
        required
        class="w-full px-4 py-3 rounded-lg border"
      >

      <input
        type="text"
        name="customer_phone"
        placeholder="Phone Number"
        required
        class="w-full px-4 py-3 rounded-lg border"
      >

      <textarea
        name="customer_address"
        placeholder="Full Address"
        required
        rows="3"
        class="w-full px-4 py-3 rounded-lg border"
      ></textarea>

      <button
        type="submit"
        name="place_order"
        class="w-full bg-[color:var(--accent)] text-white py-3 rounded-lg font-semibold hover:opacity-90"
      >
        Place Order
      </button>
    </form>
  </div>

</main>

<script src="assets/js/theme.js"></script>
</body>
</html>
