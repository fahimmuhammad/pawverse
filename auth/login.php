<?php
// auth/login.php
session_start();
require_once __DIR__ . '/../config/db.php';

// If already logged in -> redirect to appropriate area
if (isset($_SESSION['user_id'])) {
  if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header('Location: ../admin/dashboard.php');
  } else {
    header('Location: ../index.php');
  }
  exit;
}

// Ensure activity_log table exists (used for logging login events)
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

// helper to add activity
function add_activity_log($conn, $user_id, $action, $details = null) {
  $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action, details) VALUES (?, ?, ?)");
  if (!$stmt) return false;
  $stmt->bind_param("iss", $user_id, $action, $details);
  $res = $stmt->execute();
  $stmt->close();
  return $res;
}

$notice = null; // array('type'=>'error'|'success','text'=>'')

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($email === '' || $password === '') {
    $notice = ['type' => 'error', 'text' => 'Please fill in both email and password.'];
  } else {
    $stmt = $conn->prepare("SELECT id, name, email, password, role, status FROM users WHERE email = ? LIMIT 1");
    if ($stmt) {
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $res = $stmt->get_result();
      if ($res && $res->num_rows === 1) {
        $user = $res->fetch_assoc();

        if ($user['status'] !== 'active') {
          $notice = ['type' => 'error', 'text' => 'Account not active. Contact support.'];
        } elseif (password_verify($password, $user['password'])) {
          // success: set session
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['user_name'] = $user['name'];
          $_SESSION['user_email'] = $user['email'];
          $_SESSION['user_role'] = $user['role'];

          // log activity
          add_activity_log($conn, $user['id'], 'User logged in', 'IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));

          // redirect depending on role
          if ($user['role'] === 'admin') {
            header('Location: ../admin/dashboard.php');
            exit;
          } else {
            header('Location: ../index.php');
            exit;
          }
        } else {
          $notice = ['type' => 'error', 'text' => 'Invalid password.'];
        }
      } else {
        $notice = ['type' => 'error', 'text' => 'No account found with that email.'];
      }
      $stmt->close();
    } else {
      $notice = ['type' => 'error', 'text' => 'Database error (prepare failed).'];
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawVerse — Login</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="../assets/images/logo.png">

  <style>
    :root{
      --deep-1: #0f1724;
      --deep-2: #334155;
      --accent: #3b82f6;
      --bg-light: #f8fafc;
      --text-light: #0f1724;
      --text-dark: #e6eef8;
    }
    html,body { font-family: "Inter", sans-serif; transition: background 0.35s, color 0.35s; }
    body { background: var(--bg-light); color: var(--text-light); min-height:100vh; display:flex; flex-direction:column; }
    body.theme-dark { background: linear-gradient(135deg,var(--deep-1),var(--deep-2)); color: var(--text-dark); }

    .card-glass {
      background: rgba(255,255,255,0.10);
      border: 1px solid rgba(255,255,255,0.12);
      backdrop-filter: blur(8px);
    }
    body.theme-dark .card-glass {
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(255,255,255,0.08);
    }

    /* notification */
    .notice { border-radius: .6rem; padding: .65rem 1rem; display:flex; gap:.75rem; align-items:center; }
    .notice.error { background: rgba(239,68,68,0.08); color:#fee2e2; border:1px solid rgba(239,68,68,0.14); }
    .notice.success { background: rgba(16,185,129,0.06); color:#d1fae5; border:1px solid rgba(16,185,129,0.12); }
    body.theme-dark .notice.error { background: rgba(239,68,68,0.06); color:#fecaca; }
    body.theme-dark .notice.success { background: rgba(16,185,129,0.06); color:#bbf7d0; }

    /* toggle switch */
    .switch { width:50px; height:28px; border-radius:999px; padding:3px; display:inline-flex; align-items:center; background:rgba(0,0,0,0.08); cursor:pointer; }
    .switch.on { background: linear-gradient(90deg,var(--accent), #60a5fa); }
    .switch .knob { width:22px; height:22px; border-radius:999px; background:white; transform:translateX(0); transition:transform .25s; }
    .switch.on .knob { transform:translateX(22px); }
  </style>
</head>
<body>

  <!-- header -->
  <header class="w-full py-5 text-center font-bold text-2xl flex justify-center items-center gap-4">
    <a href="../index.php" class="hover:text-[color:var(--accent)] transition">🐾 PawVerse</a>

    <!-- Theme toggle -->
    <div class="flex items-center gap-2">
      <span class="text-sm">☀️</span>
      <div id="themeSwitch" class="switch" role="switch" aria-checked="false" tabindex="0">
        <div class="knob"></div>
      </div>
      <span class="text-sm">🌙</span>
    </div>
  </header>

  <!-- form -->
  <main class="flex-1 flex items-center justify-center px-6">
    <div class="w-full max-w-md card-glass rounded-3xl p-8 shadow-2xl">
      <h2 class="text-2xl font-semibold text-center mb-2">Welcome Back</h2>
      <p class="text-slate-400 text-center text-sm mb-6">Log in to continue to your PawVerse account</p>

      <?php if ($notice): ?>
        <div class="notice mb-4 <?php echo $notice['type'] === 'success' ? 'success' : 'error'; ?>">
          <?php if ($notice['type'] === 'success'): ?>
            <svg width="18" height="18" viewBox="0 0 24 24" class="opacity-90" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 6L9 17l-5-5" stroke="#10B981" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <?php else: ?>
            <svg width="18" height="18" viewBox="0 0 24 24" class="opacity-90" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 9v4m0 4h.01" stroke="#DC2626" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#DC2626" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <?php endif; ?>
          <div class="text-sm"><?php echo htmlspecialchars($notice['text']); ?></div>
        </div>
      <?php endif; ?>

      <form method="POST" class="space-y-4" novalidate>
        <div>
          <label class="text-sm text-slate-400 mb-1 block">Email</label>
          <input type="email" name="email" required placeholder="you@example.com"
            class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
        </div>

        <div>
          <label class="text-sm text-slate-400 mb-1 block">Password</label>
          <input type="password" name="password" required placeholder="••••••••"
            class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
        </div>

        <button type="submit" class="w-full bg-[color:var(--accent)] text-white py-3 rounded-lg font-semibold hover:bg-blue-500 transition">Login</button>
      </form>

      <p class="text-sm text-center text-slate-400 mt-6">
        Don’t have an account?
        <a href="register.php" class="text-[color:var(--accent)] hover:underline">Create one</a>
      </p>
    </div>
  </main>

  <footer class="text-center text-slate-500 text-sm py-4 border-t border-white/10">
    © <?php echo date('Y'); ?> PawVerse. All rights reserved.
  </footer>

  <script>
    // theme toggle (localStorage)
    const switchEl = document.getElementById('themeSwitch');
    const body = document.body;
    const savedTheme = localStorage.getItem('pawverse_theme');
    if (savedTheme === 'dark') {
      body.classList.add('theme-dark');
      switchEl.classList.add('on');
      switchEl.setAttribute('aria-checked','true');
    }
    switchEl.addEventListener('click', () => {
      const isDark = body.classList.toggle('theme-dark');
      switchEl.classList.toggle('on');
      const next = isDark ? 'dark' : 'light';
      localStorage.setItem('pawverse_theme', next);
      switchEl.setAttribute('aria-checked', isDark ? 'true' : 'false');
    });
    switchEl.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); switchEl.click(); } });
  </script>
</body>
</html>
