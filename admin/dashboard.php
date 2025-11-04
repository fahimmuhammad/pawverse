<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Admin access control
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

// Create activity_log table if not exists
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

// Helper: get client IP
function get_client_ip() {
  $keys = [
    'HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_X_REAL_IP',
    'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP',
    'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'
  ];
  foreach ($keys as $k) {
    if (!empty($_SERVER[$k])) {
      $ipList = explode(',', $_SERVER[$k]);
      foreach ($ipList as $ip) {
        $ip = trim($ip);
        if ($ip !== '') {
          return $ip === '::1' ? '127.0.0.1' : $ip;
        }
      }
    }
  }
  return 'unknown';
}

function add_activity_log($conn, $user_id, $action, $details = null) {
  $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action, details) VALUES (?, ?, ?)");
  if ($stmt) {
    $stmt->bind_param("iss", $user_id, $action, $details);
    $stmt->execute();
    $stmt->close();
  }
}

$client_ip = get_client_ip();

// Clear actions
if (isset($_POST['clear_all'])) {
  $conn->query("DELETE FROM activity_log");
  add_activity_log($conn, $_SESSION['user_id'], 'Cleared all activity logs', "IP: {$client_ip}");
  header("Location: dashboard.php");
  exit;
}
if (isset($_POST['delete_activity'])) {
  $id = intval($_POST['delete_activity']);
  $conn->query("DELETE FROM activity_log WHERE id = $id");
  add_activity_log($conn, $_SESSION['user_id'], 'Deleted single activity', "Activity ID: {$id}, IP: {$client_ip}");
  header("Location: dashboard.php");
  exit;
}

// Seed initial log
$count = $conn->query("SELECT COUNT(*) AS c FROM activity_log")->fetch_assoc();
if ($count['c'] == 0) {
  add_activity_log($conn, $_SESSION['user_id'], 'User logged in', "IP: {$client_ip}");
}

// Stats
function fetch_count($conn, $table) {
  $safe = preg_replace('/[^a-z0-9_]/i', '', $table);
  $res = $conn->query("SELECT COUNT(*) AS cnt FROM {$safe}");
  $r = $res ? $res->fetch_assoc() : ['cnt' => 0];
  return intval($r['cnt']);
}
$total_users = fetch_count($conn, 'users');
$total_orders = fetch_count($conn, 'orders');
$total_vets = fetch_count($conn, 'veterinarians');
$total_messages = fetch_count($conn, 'messages');

// Fetch activity
$activities = [];
$res = $conn->query("
  SELECT a.*, u.name AS user_name, u.email AS user_email
  FROM activity_log a
  LEFT JOIN users u ON a.user_id = u.id
  ORDER BY a.created_at DESC
  LIMIT 12
");
if ($res && $res->num_rows > 0) while ($r = $res->fetch_assoc()) $activities[] = $r;

// Fetch low-stock products
$low_stock = $conn->query("SELECT id, name, stock FROM products WHERE stock <= 5 ORDER BY stock ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>PawVerse Admin — Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
  --bg:#f8fafc;--panel:#ffffff;--muted:#64748b;--text:#0f1724;--accent:#3b82f6;
  --glass-border:rgba(2,6,23,0.05);--card-shadow:0 8px 20px rgba(15,23,36,0.05);
}
.theme-dark {
  --bg:#0f1724;--panel:#1e293b;--muted:#94a3b8;--text:#e6eef8;
  --glass-border:rgba(255,255,255,0.06);--card-shadow:0 20px 40px rgba(2,6,23,0.5);
}
body {font-family:"Inter",sans-serif;background:var(--bg);color:var(--text);transition:background .3s,color .3s;}
.panel {background:var(--panel);border:1px solid var(--glass-border);box-shadow:var(--card-shadow);border-radius:.75rem;}
.muted {color:var(--muted);}
.switch {width:52px;height:30px;border-radius:999px;padding:3px;display:inline-flex;align-items:center;cursor:pointer;background:rgba(0,0,0,0.08);}
.switch.on {background:linear-gradient(90deg,var(--accent),#60a5fa);}
.switch .knob {width:24px;height:24px;border-radius:999px;background:white;transform:translateX(0);transition:transform .25s;}
.switch.on .knob {transform:translateX(22px);}
.text-slate-400 {color:#94a3b8!important;}
th, td {text-align:left; padding:.75rem .75rem; white-space:nowrap;}
th:last-child, td:last-child {text-align:right;}
</style>
</head>
<body>

<div class="min-h-screen flex">
  <!-- SIDEBAR -->
  <aside class="fixed top-0 left-0 h-full w-64 p-6 panel z-40">
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
          <a href="dashboard.php" class="block px-4 py-2 rounded-lg bg-[color:var(--accent)] text-white font-semibold">Dashboard</a>
          <a href="products.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Products</a>
          <a href="orders.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Orders</a>
          <a href="users.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Users</a>
          <a href="veterinarians.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Veterinarians</a>
          <a href="messages.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Messages</a>
          <a href="appointments.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Appointments</a>
        </nav>
      </div>
      <form method="POST" action="../auth/logout.php" class="pt-6">
        <button class="w-full py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition">Logout</button>
      </form>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="flex-1 ml-64 p-8">
    <div class="flex flex-wrap items-center justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold">Dashboard Overview</h1>
        <p class="muted text-sm">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
      </div>
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-3">
          <div class="text-sm muted">☀️</div>
          <div id="themeSwitch" class="switch"><div class="knob"></div></div>
          <div class="text-sm muted">🌙</div>
        </div>
      </div>
    </div>

    <!-- STATS -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="panel p-4"><p class="muted text-sm">Total Users</p><h2 class="text-2xl font-bold"><?php echo $total_users; ?></h2></div>
      <div class="panel p-4"><p class="muted text-sm">Orders</p><h2 class="text-2xl font-bold"><?php echo $total_orders; ?></h2></div>
      <div class="panel p-4"><p class="muted text-sm">Veterinarians</p><h2 class="text-2xl font-bold"><?php echo $total_vets; ?></h2></div>
      <div class="panel p-4"><p class="muted text-sm">Messages</p><h2 class="text-2xl font-bold"><?php echo $total_messages; ?></h2></div>
    </section>

    <!-- LOW STOCK ALERTS -->
    <section class="panel p-4 mb-8">
      <h3 class="font-semibold mb-4">Low Stock Alerts</h3>
      <?php if ($low_stock && $low_stock->num_rows > 0): ?>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="border-b muted" style="border-color:var(--glass-border)">
                <th>Name</th>
                <th>Product Id</th>
                <th>Stock</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($p = $low_stock->fetch_assoc()): ?>
              <tr class="border-b border-slate-200 dark:border-white/10">
                <td><?php echo $p['name']; ?></td>
                <td><?php echo htmlspecialchars($p['id']); ?></td>
                <td class="text-right text-red-500 font-semibold"><?php echo $p['stock']; ?></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="muted text-sm">No low-stock products currently.</p>
      <?php endif; ?>
    </section>

    <!-- RECENT ACTIVITY -->
    <section class="panel p-4">
      <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold">Recent Activity</h3>
        <form method="POST"><button name="clear_all" class="text-red-500 hover:underline text-sm font-medium">Clear All</button></form>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="muted text-xs text-left border-b" style="border-color:var(--glass-border)">
              <th>When</th>
              <th>Action</th>
              <th>Details</th>
              <th>By (User)</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($activities)): ?>
              <tr><td colspan="5" class="py-4 muted text-center">No activity yet.</td></tr>
            <?php else: foreach ($activities as $act): ?>
              <tr class="border-b border-slate-200 dark:border-white/10">
                <td class="muted"><?php echo date('M d, Y H:i', strtotime($act['created_at'])); ?></td>
                <td class="font-medium"><?php echo htmlspecialchars($act['action']); ?></td>
                <td>
                  <?php
                    $raw = trim($act['details'] ?? '');
                    $raw = str_replace('::1', '127.0.0.1', $raw);
                    echo htmlspecialchars($raw ?: '—');
                  ?>
                </td>
                <td>
                  <?php if ($act['user_name']): ?>
                    <strong><?php echo htmlspecialchars($act['user_name']); ?></strong><br>
                    <span class="text-xs muted"><?php echo htmlspecialchars($act['user_email']); ?></span>
                  <?php else: ?>
                    <span class="muted text-xs">System</span>
                  <?php endif; ?>
                </td>
                <td class="text-right">
                  <form method="POST" style="display:inline;">
                    <button type="submit" name="delete_activity" value="<?php echo $act['id']; ?>" class="text-red-500 hover:underline text-xs font-semibold">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <footer class="mt-8 text-sm muted text-center border-t border-slate-200 dark:border-white/10 pt-6">
      © <?php echo date('Y'); ?> PawVerse Admin Panel
    </footer>
  </main>
</div>

<script>
const switchEl=document.getElementById('themeSwitch'),body=document.body,saved=localStorage.getItem('pawverse_theme');
if(saved==='dark'){body.classList.add('theme-dark');switchEl.classList.add('on');}
switchEl.addEventListener('click',()=>{const isDark=body.classList.toggle('theme-dark');switchEl.classList.toggle('on');localStorage.setItem('pawverse_theme',isDark?'dark':'light');});
</script>
</body>
</html>
