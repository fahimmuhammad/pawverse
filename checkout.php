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

/* -------------------------------
   CART CHECK
-------------------------------- */
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$userId = $_SESSION['user_id'];

/* -------------------------------
   CALCULATE TOTAL
-------------------------------- */
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['qty'];
}

/* -------------------------------
   PLACE ORDER (THIS IS THE KEY)
-------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {

    $stmt = $conn->prepare("
        INSERT INTO orders (user_id, total_amount, status, created_at)
        VALUES (?, ?, 'pending', NOW())
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("id", $userId, $total);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->close();

    // IMPORTANT: clear cart ONLY AFTER successful insert
    $_SESSION['cart'] = [];

    header("Location: order_success.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout — PawVerse</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<?php include 'includes/header.php'; ?>

<div class="max-w-3xl mx-auto mt-16 bg-white p-8 rounded-xl shadow">
  <h1 class="text-2xl font-bold mb-6">Checkout</h1>

  <div class="space-y-3 mb-6">
    <?php foreach ($_SESSION['cart'] as $item): ?>
      <div class="flex justify-between">
        <span><?php echo htmlspecialchars($item['name']); ?> × <?php echo $item['qty']; ?></span>
        <span>$<?php echo number_format($item['price'] * $item['qty'], 2); ?></span>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="border-t pt-4 flex justify-between font-bold text-lg">
    <span>Total</span>
    <span>$<?php echo number_format($total, 2); ?></span>
  </div>

  <form method="POST" class="mt-6">
    <button
      type="submit"
      name="place_order"
      class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700">
      Place Order
    </button>
  </form>
</div>

</body>
</html>
