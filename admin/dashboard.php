<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Admin-only access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

// Create activity_log if missing
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
  if (!$stmt) return false;
  $stmt->bind_param("iss", $user_id, $action, $details);
  $stmt->execute();
  $stmt->close();
  return true;
}

// Normalize localhost IP
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if ($ip === '::1') $ip = '127.0.0.1';

// Seed a login activity if empty (for initial load)
$count = $conn->query("SELECT COUNT(*) AS c FROM activity_log")->fetch_assoc();
if ($count['c'] == 0) {
  add_activity_log($conn, $_SESSION['user_id'], 'User logged in', "IP: $ip");
}

// Fetch dashboard stats
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

// Fetch recent activity
$activities = [];
$res = $conn->query("SELECT * FROM activity_log ORDER BY created_at DESC LIMIT 12");
if ($res && $res->num_rows > 0) {
  while ($r = $res->fetch_assoc()) $activities[] = $r;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>PawVerse — Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg:#f8fafc;--panel:#ffffff;--muted:#64748b;--text:#0f1724;--accent:#3b82f6;
      --glass-border:rgba(2,6,23,0.05);--card-shadow:0 6px 18px rgba(15,23,36,0.05);
    }
    .theme-dark{
      --bg:linear-gradient(135deg,#0f1724,#334155);
      --panel:rgba(255,255,255,0.05);
      --muted:#94a3b8;--text:#e6eef8;
      --glass-border:rgba(255,255,255,0.06);
      --card-shadow:0 20px 40px rgba(2,6,23,0.5);
    }
    body{font-family:"Inter",sans-serif;background:var(--bg);color:var(--text);transition:background 0.35s,color 0.35s;}
    .panel{background:var(--panel);border:1px solid var(--glass-border);box-shadow:var(--card-shadow);border-radius:.75rem;}
    .muted{color:var(--muted);}
    .switch{width:52px;height:30px;border-radius:999px;padding:3px;display:inline-flex;align-items:center;cursor:pointer;background:rgba(0,0,0,0.08);}
    .switch.on{background:linear-gradient(90deg,var(--accent),#60a5fa);}
    .switch .knob{width:24px;height:24px;border-radius:999px;background:white;transform:translateX(0);transition:transform .25s;}
    .switch.on .knob{transform:translateX(22px);}
    .text-slate-400{color:#94a3b8!important;}
  </style>
</head>
<body>

<div class="min-h-screen flex">
  <!-- Sidebar -->
  <aside class="fixed md:static inset-y-0 left-0 w-64 p-6 panel z-40">
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
          <a href="#" class="block px-4 py-2 rounded-lg bg-[color:var(--accent)] text-white font-semibold">Dashboard</a>
          <a href="products.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Products</a>
          <a href="orders.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Orders</a>
          <a href="users.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Users</a>
          <a href="vets.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Veterinarians</a>
          <a href="messages.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Messages</a>
        </nav>
      </div>

      <form method="POST" action="../auth/logout.php" class="pt-6">
        <button class="w-full py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition">Logout</button>
      </form>
    </div>
  </aside>

  <!-- Main -->
  <main class="flex-1 md:ml-64 p-6 md:p-8">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold">Dashboard Overview</h1>
        <p class="muted text-sm">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
      </div>

      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
          <span class="text-sm muted">☀️</span>
          <div id="themeSwitch" class="switch"><div class="knob"></div></div>
          <span class="text-sm muted">🌙</span>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-10 h-10 rounded-full bg-[color:var(--accent)] flex items-center justify-center text-white font-bold">A</div>
          <div class="text-right">
            <div class="font-semibold"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
            <div class="text-xs muted"><?php echo htmlspecialchars($_SESSION['user_email']); ?></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="panel p-4"><p class="muted text-sm">Total Users</p><h2 class="text-2xl font-bold"><?php echo $total_users; ?></h2></div>
      <div class="panel p-4"><p class="muted text-sm">Orders</p><h2 class="text-2xl font-bold"><?php echo $total_orders; ?></h2></div>
      <div class="panel p-4"><p class="muted text-sm">Veterinarians</p><h2 class="text-2xl font-bold"><?php echo $total_vets; ?></h2></div>
      <div class="panel p-4"><p class="muted text-sm">Messages</p><h2 class="text-2xl font-bold"><?php echo $total_messages; ?></h2></div>
    </section>

    <!-- Recent Activity -->
    <section class="panel p-4">
      <h3 class="font-semibold mb-4">Recent Activity</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead>
            <tr class="muted text-xs text-left border-b" style="border-color:var(--glass-border)">
              <th class="pb-3">When</th>
              <th class="pb-3">Action</th>
              <th class="pb-3">Details</th>
              <th class="pb-3 text-right">By (user)</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($activities)): ?>
              <tr><td colspan="4" class="py-4 muted text-center">No activity yet.</td></tr>
            <?php else: ?>
              <?php foreach ($activities as $act): ?>
              <tr class="border-b" style="border-color:var(--glass-border)">
                <td class="py-3 text-sm muted"><?php echo date('M d, Y H:i', strtotime($act['created_at'])); ?></td>
                <td class="py-3 font-medium"><?php echo htmlspecialchars($act['action']); ?></td>

                <!-- Smart Details Column -->
                <td class="py-3 text-sm text-slate-600">
                  <?php
                    $details = str_replace('::1', '127.0.0.1', $act['details'] ?? '');
                    if ($act['user_id']) {
                      $stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ? LIMIT 1");
                      $stmt->bind_param("i", $act['user_id']);
                      $stmt->execute();
                      $res = $stmt->get_result();
                      if ($usr = $res->fetch_assoc()) {
                        echo htmlspecialchars($usr['name']) .
                          ' <span class="text-slate-400">(' . htmlspecialchars($usr['email']) . ')</span>';
                        if (!empty($details)) {
                          echo ' — <span class="muted">' . htmlspecialchars($details) . '</span>';
                        }
                      } else {
                        echo htmlspecialchars($details);
                      }
                      $stmt->close();
                    } else {
                      echo htmlspecialchars($details);
                    }
                  ?>
                </td>

                <!-- By (user) Column -->
                <td class="py-3 text-right text-sm muted">
                  <?php
                    if ($act['user_id']) {
                      $uStmt = $conn->prepare("SELECT name, email FROM users WHERE id = ? LIMIT 1");
                      $uStmt->bind_param("i", $act['user_id']);
                      $uStmt->execute();
                      $uRes = $uStmt->get_result();
                      if ($uRow = $uRes->fetch_assoc()) {
                        echo htmlspecialchars($uRow['name']) .
                             ' <span class="text-slate-400 text-sm">(' .
                             htmlspecialchars($uRow['email']) . ')</span>';
                      } else {
                        echo 'User #' . intval($act['user_id']);
                      }
                      $uStmt->close();
                    } else {
                      echo '-';
                    }
                  ?>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <footer class="mt-8 text-sm muted">© <?php echo date('Y'); ?> PawVerse Admin Panel</footer>
  </main>
</div>

<script>
  const switchEl = document.getElementById('themeSwitch');
  const body = document.body;
  const saved = localStorage.getItem('pawverse_theme');
  if (saved === 'dark') {
    body.classList.add('theme-dark');
    switchEl.classList.add('on');
  }
  switchEl.addEventListener('click',()=>{
    const isDark = body.classList.toggle('theme-dark');
    switchEl.classList.toggle('on');
    localStorage.setItem('pawverse_theme', isDark?'dark':'light');
  });
</script>

</body>
</html>
