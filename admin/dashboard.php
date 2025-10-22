<?php
// admin/dashboard.php
// -------------------
// Admin dashboard: dynamic stats + recent activity with auto-seeding.
// Requires: config/db.php (defines $conn) and a logged-in admin in $_SESSION['user_role'] === 'admin'.
// Paste this file to /admin/dashboard.php (overwrite existing).

session_start();
require_once __DIR__ . '/../config/db.php';

// --------------------
// Access control (admin only)
// --------------------
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    // Not logged in as admin — redirect to login
    header('Location: ../auth/login.php');
    exit;
}

// --------------------
// Utility: ensure activity_log table exists
// --------------------
$createActivityTableSQL = "
CREATE TABLE IF NOT EXISTS activity_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  action VARCHAR(191) NOT NULL,
  details TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (created_at),
  INDEX (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";
$conn->query($createActivityTableSQL); // ignore result; if fails, subsequent queries will reveal errors

// --------------------
// Helper: add an activity log entry
// Use this function from any page to record events:
// add_activity_log($conn, $user_id_or_null, 'User logged in', 'IP: ...');
// --------------------
function add_activity_log($conn, $user_id, $action, $details = null) {
    $sql = "INSERT INTO activity_log (user_id, action, details) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return false;
    $stmt->bind_param("iss", $user_id, $action, $details);
    $res = $stmt->execute();
    $stmt->close();
    return $res;
}

// --------------------
// Auto-seed activity_log if empty (makes dashboard useful right away).
// This inspects recent rows in orders/messages/users/vets and creates summary logs.
// It runs only when activity_log has zero rows.
// --------------------
$check = $conn->query("SELECT COUNT(*) AS cnt FROM activity_log");
$row = $check->fetch_assoc();
if ($row && intval($row['cnt']) === 0) {
    // 1) Recent orders -> "Placed an order"
    $orderQ = $conn->query("SELECT id, user_id, total_amount, created_at FROM orders ORDER BY created_at DESC LIMIT 8");
    if ($orderQ) {
        while ($o = $orderQ->fetch_assoc()) {
            $details = "Order #{$o['id']} — Amount: {$o['total_amount']}";
            add_activity_log($conn, $o['user_id'] ?? null, 'Placed an order', $details);
        }
    }

    // 2) Recent messages -> "Message sent"
    $msgQ = $conn->query("SELECT id, name, email, subject, created_at FROM messages ORDER BY created_at DESC LIMIT 8");
    if ($msgQ) {
        while ($m = $msgQ->fetch_assoc()) {
            $details = "Message #{$m['id']} — {$m['subject']} — From: {$m['name']} ({$m['email']})";
            add_activity_log($conn, null, 'Message received', $details);
        }
    }

    // 3) Recent registrations -> "New user registered"
    $userQ = $conn->query("SELECT id, name, email, created_at FROM users ORDER BY created_at DESC LIMIT 8");
    if ($userQ) {
        while ($u = $userQ->fetch_assoc()) {
            $details = "User #{$u['id']} — {$u['name']} ({$u['email']})";
            add_activity_log($conn, $u['id'], 'New user registered', $details);
        }
    }

    // 4) Recent vets added -> "Vet added"
    // Table name used here: `vets` (you confirmed this name). If you used `veterinarians`, change this query accordingly.
    $vetQ = $conn->query("SELECT id, name, specialty, created_at FROM vets ORDER BY created_at DESC LIMIT 8");
    if ($vetQ) {
        while ($v = $vetQ->fetch_assoc()) {
            $details = "Vet #{$v['id']} — {$v['name']} ({$v['specialty']})";
            add_activity_log($conn, null, 'Vet added', $details);
        }
    }
}

// --------------------
// Fetch dashboard counters (live from DB)
// --------------------
function fetch_count($conn, $table) {
    $safe = preg_replace('/[^a-z0-9_]/i', '', $table); // basic safety
    $res = $conn->query("SELECT COUNT(*) AS cnt FROM {$safe}");
    if (!$res) return 0;
    $r = $res->fetch_assoc();
    return intval($r['cnt'] ?? 0);
}

$total_users = fetch_count($conn, 'users');
$total_orders = fetch_count($conn, 'orders');
$total_vets = fetch_count($conn, 'vets');
$total_messages = fetch_count($conn, 'messages');

// --------------------
// Fetch recent activity (most recent 12)
// --------------------
$activityStmt = $conn->prepare("SELECT id, user_id, action, details, created_at FROM activity_log ORDER BY created_at DESC LIMIT 12");
$activityStmt->execute();
$activityRes = $activityStmt->get_result();
$activities = $activityRes->fetch_all(MYSQLI_ASSOC);
$activityStmt->close();

// --------------------
// (Optional) Fetch a few recent orders/messages for details panel (example)
// --------------------
// You can add more widgets below if you want to show lists of orders/messages.
// --------------------
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawVerse — Admin Dashboard</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="../assets/js/theme.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --bg: #f8fafc;
      --panel: #ffffff;
      --muted: #64748b;
      --text: #0f1724;
      --accent: #3b82f6;
      --glass-border: rgba(2,6,23,0.04);
      --card-shadow: 0 8px 24px rgba(15,23,36,0.04);
    }
    .theme-dark{
      --bg: linear-gradient(135deg,#0f1724,#334155);
      --panel: rgba(255,255,255,0.04);
      --muted: #94a3b8;
      --text: #e6eef8;
      --glass-border: rgba(255,255,255,0.06);
      --card-shadow: 0 20px 40px rgba(2,6,23,0.5);
    }
    .theme-transition * {
      transition: background-color 350ms cubic-bezier(.2,.9,.25,1), color 300ms ease, border-color 300ms ease, box-shadow 300ms ease;
    }
    html,body{font-family:"Inter",system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial;}
    body{background:var(--bg);color:var(--text);}
    .panel{background:var(--panel);border:1px solid var(--glass-border);box-shadow:var(--card-shadow);border-radius:.75rem;}
    .muted{color:var(--muted);}
    .accent{color:var(--accent);}
    .switch{width:52px;height:30px;border-radius:999px;padding:3px;display:inline-flex;align-items:center;cursor:pointer;background:rgba(0,0,0,0.06);}
    .switch .knob{width:24px;height:24px;border-radius:999px;background:white;transform:translateX(0);transition:transform .24s;}
    .switch.on{background:linear-gradient(90deg,var(--accent),#60a5fa);}
    .switch.on .knob{transform:translateX(22px);}
    .fade-up{transform:translateY(12px);opacity:0;transition:all .56s cubic-bezier(.16,.84,.24,1);}
    .fade-up.in-view{transform:translateY(0);opacity:1;}
  </style>
</head>
<body class="theme-transition">

  <div class="min-h-screen flex">
    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar fixed md:static inset-y-0 left-0 w-64 p-6 panel z-40">
      <div class="flex flex-col h-full justify-between">
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
            <a href="products.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/6 transition">Products</a>
            <a href="orders.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/6 transition">Orders</a>
            <a href="users.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/6 transition">Users</a>
            <a href="vets.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/6 transition">Veterinarians</a>
            <a href="messages.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/6 transition">Messages</a>
          </nav>
        </div>

        <div>
          <form method="POST" action="../auth/logout.php">
            <button id="logoutBtn" type="submit" class="w-full py-2 rounded-lg text-white bg-red-500 hover:bg-red-600 transition">Logout</button>
          </form>
        </div>
      </div>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 md:ml-64 p-6 md:p-8">
      <!-- Top bar -->
      <div class="flex items-center justify-between gap-4 mb-8">
        <div>
          <h1 class="text-2xl font-bold">Dashboard Overview</h1>
          <div class="text-sm muted">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></div>
        </div>

        <div class="flex items-center gap-4">
          <div class="flex items-center gap-3">
            <div class="text-sm muted">Light</div>
            <div id="themeSwitch" role="switch" aria-checked="false" tabindex="0" class="switch" title="Toggle dark mode"><div class="knob"></div></div>
            <div class="text-sm muted">Dark</div>
          </div>

          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[color:var(--accent)] to-blue-400 flex items-center justify-center text-white">A</div>
            <div class="text-right">
              <div class="font-semibold">Admin</div>
              <div class="text-xs muted"><?php echo htmlspecialchars($_SESSION['user_email'] ?? 'admin@pawverse.com'); ?></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Stats -->
      <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="panel p-4 fade-up">
          <div class="muted text-sm">Total Users</div>
          <div class="text-2xl font-bold"><?php echo $total_users; ?></div>
        </div>

        <div class="panel p-4 fade-up" data-delay="80">
          <div class="muted text-sm">Orders</div>
          <div class="text-2xl font-bold"><?php echo $total_orders; ?></div>
        </div>

        <div class="panel p-4 fade-up" data-delay="160">
          <div class="muted text-sm">Veterinarians</div>
          <div class="text-2xl font-bold"><?php echo $total_vets; ?></div>
        </div>

        <div class="panel p-4 fade-up" data-delay="240">
          <div class="muted text-sm">Messages</div>
          <div class="text-2xl font-bold"><?php echo $total_messages; ?></div>
        </div>
      </section>

      <!-- Recent activity -->
      <section class="panel p-4 fade-up" data-delay="300">
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
                <tr><td colspan="4" class="py-4 muted">No activity yet.</td></tr>
              <?php else: ?>
                <?php foreach ($activities as $act): ?>
                  <tr class="border-b" style="border-color:var(--glass-border)">
                    <td class="py-3 text-sm muted"><?php echo date('M d, Y H:i', strtotime($act['created_at'])); ?></td>
                    <td class="py-3 font-medium"><?php echo htmlspecialchars($act['action']); ?></td>
                    <td class="py-3 text-sm text-slate-600"><?php echo htmlspecialchars($act['details']); ?></td>
                    <td class="py-3 text-right text-sm muted">
                      <?php
                        if ($act['user_id']) {
                          // attempt to display user name for activity (if present)
                          $uStmt = $conn->prepare("SELECT name, email FROM users WHERE id = ? LIMIT 1");
                          $uStmt->bind_param("i", $act['user_id']);
                          $uStmt->execute();
                          $uRes = $uStmt->get_result();
                          if ($uRow = $uRes->fetch_assoc()) {
                            echo htmlspecialchars($uRow['name']);
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
    // progressive fade-in observer
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if(entry.isIntersecting) entry.target.classList.add('in-view');
      });
    }, { threshold: 0.12 });

    document.querySelectorAll('.fade-up').forEach((el, idx) => {
      const delay = el.dataset.delay ? Number(el.dataset.delay) : (idx * 60);
      el.style.transitionDelay = `${delay}ms`;
      io.observe(el);
    });

    // THEME TOGGLE: connect to localStorage & apply theme (default: light)
    const switchEl = document.getElementById('themeSwitch');
    const body = document.body;
    function applyTheme(theme) {
      if(theme === 'dark') {
        body.classList.add('theme-dark');
        switchEl.classList.add('on');
        switchEl.setAttribute('aria-checked','true');
      } else {
        body.classList.remove('theme-dark');
        switchEl.classList.remove('on');
        switchEl.setAttribute('aria-checked','false');
      }
      body.classList.add('theme-transition');
      setTimeout(()=> body.classList.remove('theme-transition'), 420);
    }
    // init from localStorage
    const saved = localStorage.getItem('pawverse_theme');
    applyTheme(saved === 'dark' ? 'dark' : 'light');
    // click & keyboard
    switchEl.addEventListener('click', () => {
      const next = body.classList.contains('theme-dark') ? 'light' : 'dark';
      applyTheme(next);
      localStorage.setItem('pawverse_theme', next);
    });
    switchEl.addEventListener('keydown', (e) => { if(e.key === 'Enter' || e.key === ' ') { e.preventDefault(); switchEl.click(); } });

    // accessibility
    switchEl.setAttribute('role','switch');
    switchEl.setAttribute('aria-label','Toggle dark mode');
  </script>
</body>
</html>
