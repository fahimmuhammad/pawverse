<?php
session_start();
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: auth/login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

/* FETCH USER ORDERS */
$stmt = $conn->prepare("
  SELECT
    id,
    items,
    total_amount,
    payment_method,
    payment_status,
    status,
    created_at
  FROM orders
  WHERE user_id = ?
  ORDER BY created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders — PawVerse</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<style>
:root{
  --bg:#f8fafc;
  --text:#0f172a;
  --muted:#64748b;
  --panel:#ffffff;
  --accent:#3b82f6;
}
.theme-dark{
  --bg:#0f172a;
  --text:#e5e7eb;
  --muted:#94a3b8;
  --panel:#1e293b;
  --accent:#60a5fa;
}
body{
  background:var(--bg);
  color:var(--text);
  font-family:"Inter",sans-serif;
  transition:.3s;
}
.panel{background:var(--panel);}
.muted{color:var(--muted);}
.switch{
  width:52px;height:30px;border-radius:999px;
  padding:3px;display:inline-flex;align-items:center;
  cursor:pointer;background:rgba(0,0,0,.15);
}
.switch.on{background:linear-gradient(90deg,var(--accent),#93c5fd);}
.switch .knob{
  width:24px;height:24px;border-radius:999px;
  background:#fff;transition:.25s;
}
.switch.on .knob{transform:translateX(22px);}
</style>
</head>

<body>

<!-- HEADER -->
<?php include 'includes/header.php'; ?>

<main class="max-w-7xl mx-auto px-6 pt-28 pb-16">

  <h1 class="text-3xl font-extrabold mb-8">My Orders</h1>

  <?php if (empty($orders)): ?>
    <div class="panel p-6 rounded-xl text-center muted">
      You haven’t placed any orders yet.
    </div>
  <?php else: ?>

  <div class="space-y-6">
    <?php foreach ($orders as $order): ?>
      <div class="panel rounded-xl p-6 shadow">

        <div class="flex flex-wrap justify-between gap-4 mb-4">
          <div>
            <div class="text-sm muted">Order ID</div>
            <div class="font-semibold">#<?php echo $order['id']; ?></div>
          </div>

          <div>
            <div class="text-sm muted">Date</div>
            <div class="font-semibold">
              <?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?>
            </div>
          </div>

          <div>
            <div class="text-sm muted">Status</div>
            <div class="font-semibold capitalize text-[color:var(--accent)]">
              <?php echo htmlspecialchars($order['status']); ?>
            </div>
          </div>

          <div>
            <div class="text-sm muted">Total</div>
            <div class="font-bold text-lg">
              $<?php echo number_format($order['total_amount'], 2); ?>
            </div>
          </div>
        </div>

        <div class="border-t border-white/10 pt-4">
          <div class="text-sm muted mb-2">Items</div>
          <div class="text-sm leading-relaxed">
            <?php
              $items = json_decode($order['items'], true);
              if (is_array($items)):
                foreach ($items as $item):
                  echo htmlspecialchars($item['name']) . 
                       " × " . intval($item['qty']) . "<br>";
                endforeach;
              else:
                echo "-";
              endif;
            ?>
          </div>
        </div>

        <div class="mt-4 flex flex-wrap gap-6 text-sm">
          <div>
            <span class="muted">Payment:</span>
            <strong><?php echo strtoupper($order['payment_method']); ?></strong>
          </div>
          <div>
            <span class="muted">Payment Status:</span>
            <strong><?php echo ucfirst($order['payment_status']); ?></strong>
          </div>
        </div>

      </div>
    <?php endforeach; ?>
  </div>

  <?php endif; ?>

</main>

<footer class="bg-slate-900 text-slate-300 py-6 text-center">
© <?php echo date('Y'); ?> PawVerse. All rights reserved.
</footer>

<script src="assets/js/theme.js"></script>
</body>
</html>
