<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

/* Default pending status for new orders */
$conn->query("
  ALTER TABLE orders 
  MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'pending';
");

$conn->query("
  CREATE TABLE IF NOT EXISTS activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(191) NOT NULL,
    details TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (created_at),
    INDEX (user_id)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

function add_activity_log($conn, $user_id, $action, $details = null) {
  $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action, details) VALUES (?, ?, ?)");
  if ($stmt) {
    $stmt->bind_param("iss", $user_id, $action, $details);
    $stmt->execute();
    $stmt->close();
  }
}

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if ($ip === '::1') $ip = '127.0.0.1';

/* AJAX handlers */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_action'])) {
  header('Content-Type: application/json; charset=utf-8');
  $action = $_POST['ajax_action'];

  if ($action === 'update_status') {
    $order_id = intval($_POST['order_id']);
    $status = trim($_POST['status']);
    $allowed = ['pending','processing','shipped','delivered','cancelled'];
    if (!in_array($status, $allowed, true)) {
      echo json_encode(['ok'=>false,'msg'=>'Invalid status']);
      exit;
    }
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $ok = $stmt->execute();
    $stmt->close();
    if ($ok) {
      add_activity_log($conn, $_SESSION['user_id'], 'Order status updated', "Order #{$order_id} → {$status} (IP: {$ip})");
      echo json_encode(['ok'=>true,'msg'=>'Order status updated']);
    } else echo json_encode(['ok'=>false,'msg'=>'Failed to update']);
    exit;
  }

  if ($action === 'delete_order') {
    $id = intval($_POST['order_id']);
    $del = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $del->bind_param("i", $id);
    $ok = $del->execute();
    $del->close();
    if ($ok) {
      add_activity_log($conn, $_SESSION['user_id'], 'Order deleted', "Order #{$id} (IP: {$ip})");
      echo json_encode(['ok'=>true,'msg'=>'Order deleted']);
    } else echo json_encode(['ok'=>false,'msg'=>'Delete failed']);
    exit;
  }

  echo json_encode(['ok'=>false,'msg'=>'Unknown action']);
  exit;
}

/* Fetch orders */
$orders = $conn->query("
  SELECT o.id, o.user_id, o.total_amount, o.status, o.created_at,
         u.name AS user_name, u.email AS user_email
  FROM orders o
  LEFT JOIN users u ON o.user_id = u.id
  ORDER BY o.created_at DESC
");
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>PawVerse Admin — Orders</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {
  --bg:#f8fafc; --panel:#ffffff; --text:#0f172a; --muted:#64748b; --accent:#3b82f6; --border:#e2e8f0;
}
.theme-dark {
  --bg:#0f172a; --panel:#1e293b; --text:#f1f5f9; --muted:#cbd5e1; --accent:#60a5fa; --border:#334155;
}
body{font-family:"Inter",sans-serif;background:var(--bg);color:var(--text);transition:.3s;}
.panel{background:var(--panel);border:1px solid var(--border);border-radius:.75rem;box-shadow:0 4px 20px rgba(0,0,0,0.04);}
.muted{color:var(--muted);}
.switch{width:52px;height:30px;border-radius:999px;padding:3px;display:inline-flex;align-items:center;cursor:pointer;background:rgba(0,0,0,0.1);}
.switch.on{background:linear-gradient(90deg,var(--accent),#93c5fd);}
.switch .knob{width:24px;height:24px;border-radius:999px;background:white;transition:transform .24s;}
.switch.on .knob{transform:translateX(22px);}
table{width:100%;border-collapse:collapse;}
th,td{padding:.9rem;border-bottom:1px solid var(--border);text-align:left;}
th{color:var(--muted);font-size:.875rem;}
select.status-select{
  padding:.45rem .9rem;
  border-radius:.5rem;
  font-weight:600;
  border:1px solid var(--border);
  cursor:pointer;
  transition:.2s;
}
select.status-select:hover{border-color:var(--accent);}
body.theme-dark select.status-select{background:var(--panel);color:var(--text);}
.toast{position:fixed;top:1rem;left:50%;transform:translateX(-50%)translateY(-10px);
  padding:.8rem 1.5rem;border-radius:.5rem;font-weight:600;opacity:0;pointer-events:none;transition:.3s;z-index:1000;}
.toast.show{opacity:1;transform:translateX(-50%)translateY(0);pointer-events:auto;}
.toast-success{background:#16a34a;color:white;}
.toast-error{background:#ef4444;color:white;}
</style>
</head>
<body>

<div id="toast" class="toast"></div>

<div class="flex min-h-screen">
  <!-- Sidebar -->
  <aside class="fixed md:static inset-y-0 left-0 w-64 p-6 panel">
    <div class="flex flex-col justify-between h-full">
      <div>
        <div class="flex items-center gap-3 mb-8">
          <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-[color:var(--accent)] text-white font-bold">PV</div>
          <div>
            <h3 class="text-lg font-bold">PawVerse Admin</h3>
            <div class="text-xs muted">Control panel</div>
          </div>
        </div>
        <nav class="space-y-2">
          <a href="dashboard.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Dashboard</a>
          <a href="products.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Products</a>
          <a href="orders.php" class="block px-4 py-2 rounded-lg bg-[color:var(--accent)] text-white font-semibold">Orders</a>
          <a href="users.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Users</a>
          <a href="veterinarians.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Veterinarians</a>
          <a href="messages.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Messages</a>
        </nav>
      </div>
      <form method="POST" action="../auth/logout.php" class="pt-6">
        <button class="w-full py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition">Logout</button>
      </form>
    </div>
  </aside>

  <!-- Main -->
  <main class="flex-1 p-6 md:p-8 w-full">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold">Orders</h1>
        <p class="muted text-sm">Manage and update all customer orders</p>
      </div>
      <div class="flex items-center gap-3">
        <span class="text-sm muted">☀️</span>
        <div id="themeSwitch" class="switch"><div class="knob"></div></div>
        <span class="text-sm muted">🌙</span>
      </div>
    </div>

    <div class="panel p-4 overflow-x-auto w-full">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>User</th>
            <th>Email</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($orders && $orders->num_rows): while ($o=$orders->fetch_assoc()): ?>
          <tr>
            <td><?php echo $o['id']; ?></td>
            <td><?php echo htmlspecialchars($o['user_name'] ?: 'Guest'); ?></td>
            <td class="muted"><?php echo htmlspecialchars($o['user_email'] ?: '-'); ?></td>
            <td><strong>$<?php echo number_format($o['total_amount'],2); ?></strong></td>
            <td>
              <select data-id="<?php echo $o['id']; ?>" class="status-select">
                <?php foreach(['pending','processing','shipped','delivered','cancelled'] as $s): ?>
                  <option value="<?php echo $s; ?>" <?php if($o['status']===$s) echo'selected'; ?>><?php echo ucfirst($s); ?></option>
                <?php endforeach; ?>
              </select>
            </td>
            <td class="muted"><?php echo date('M d, Y', strtotime($o['created_at'])); ?></td>
            <td class="text-right">
              <button onclick="deleteOrder(<?php echo $o['id']; ?>)" class="text-red-600 hover:text-red-800 font-semibold">Delete</button>
            </td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="7" class="text-center py-4 muted">No orders available</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <footer class="mt-8 text-sm muted text-center">© <?php echo date('Y'); ?> PawVerse Admin Panel</footer>
  </main>
</div>

<script>
const toast=document.getElementById('toast');
function showToast(type,msg){
  toast.className='toast show toast-'+type;
  toast.textContent=msg;
  setTimeout(()=>toast.className='toast',3000);
}
const switchEl=document.getElementById('themeSwitch');
const body=document.body;
const saved=localStorage.getItem('pawverse_theme');
if(saved==='dark'){body.classList.add('theme-dark');switchEl.classList.add('on');}
switchEl.addEventListener('click',()=>{const isDark=body.classList.toggle('theme-dark');switchEl.classList.toggle('on');localStorage.setItem('pawverse_theme',isDark?'dark':'light');});

document.querySelectorAll('.status-select').forEach(sel=>{
  sel.addEventListener('change',()=>{
    const id=sel.dataset.id;
    const status=sel.value;
    const fd=new FormData();
    fd.append('ajax_action','update_status');
    fd.append('order_id',id);
    fd.append('status',status);
    fetch('orders.php',{method:'POST',body:fd})
      .then(r=>r.json())
      .then(j=>{showToast(j.ok?'success':'error',j.msg);})
      .catch(()=>showToast('error','Network error'));
  });
});

function deleteOrder(id){
  if(!confirm('Delete this order?'))return;
  const fd=new FormData();
  fd.append('ajax_action','delete_order');
  fd.append('order_id',id);
  fetch('orders.php',{method:'POST',body:fd})
    .then(r=>r.json())
    .then(j=>{showToast(j.ok?'success':'error',j.msg);if(j.ok)setTimeout(()=>location.reload(),800);})
    .catch(()=>showToast('error','Network error'));
}
</script>
</body>
</html>
