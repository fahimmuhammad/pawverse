<?php
// auth/register.php
session_start();
require_once __DIR__ . '/../config/db.php';

// If user already logged in -> redirect
if (isset($_SESSION['user_id'])) {
  header("Location: ../index.php");
  exit;
}

// Ensure activity_log table exists
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
  $res = $stmt->execute();
  $stmt->close();
  return $res;
}

$notice = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');
  $confirm = trim($_POST['confirm_password'] ?? '');

  // basic validation
  if ($name === '' || $email === '' || $password === '' || $confirm === '') {
    $notice = ['type'=>'error','text'=>'Please fill in all fields.'];
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $notice = ['type'=>'error','text'=>'Please provide a valid email address.'];
  } elseif (strlen($password) < 6) {
    $notice = ['type'=>'error','text'=>'Password must be at least 6 characters.'];
  } elseif ($password !== $confirm) {
    $notice = ['type'=>'error','text'=>'Passwords do not match.'];
  } else {
    // check for existing email
    $check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $check->bind_param("s", $email);
    $check->execute();
    $r = $check->get_result();
    if ($r && $r->num_rows > 0) {
      $notice = ['type'=>'error','text'=>'An account with this email already exists.'];
      $check->close();
    } else {
      $check->close();
      // create user (default role: customer, status: active)
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, status, created_at) VALUES (?, ?, ?, 'customer', 'active', NOW())");
      if ($stmt) {
        $stmt->bind_param("sss", $name, $email, $hash);
        if ($stmt->execute()) {
          $newId = $stmt->insert_id;
          $stmt->close();

          // log registration
          add_activity_log($conn, $newId, 'New user registered', "User created via registration form");

          // auto-login user after registration
          $_SESSION['user_id'] = $newId;
          $_SESSION['user_name'] = $name;
          $_SESSION['user_email'] = $email;
          $_SESSION['user_role'] = 'customer';

          // success notice (in case JS disabled) and redirect to front
          header("Location: ../index.php");
          exit;
        } else {
          $notice = ['type'=>'error','text'=>'Failed to create account (database error).'];
        }
      } else {
        $notice = ['type'=>'error','text'=>'Database error (prepare failed).'];
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawVerse — Register</title>

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
    html,body { font-family:"Inter",sans-serif; transition: background 0.35s, color 0.35s; }
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

    .notice { border-radius: .6rem; padding: .65rem 1rem; display:flex; gap:.75rem; align-items:center; }
    .notice.error { background: rgba(239,68,68,0.08); color:#fee2e2; border:1px solid rgba(239,68,68,0.14); }
    .notice.success { background: rgba(16,185,129,0.06); color:#d1fae5; border:1px solid rgba(16,185,129,0.12); }
    body.theme-dark .notice.error { background: rgba(239,68,68,0.06); color:#fecaca; }
    body.theme-dark .notice.success { background: rgba(16,185,129,0.06); color:#bbf7d0; }

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

    <div class="flex items-center gap-2">
      <span class="text-sm">☀️</span>
      <div id="themeSwitch" class="switch" role="switch" aria-checked="false" tabindex="0">
        <div class="knob"></div>
      </div>
      <span class="text-sm">🌙</span>
    </div>
  </header>

  <!-- register form -->
  <main class="flex-1 flex items-center justify-center px-6">
    <div class="w-full max-w-md card-glass rounded-3xl p-8 shadow-2xl">
      <h2 class="text-2xl font-semibold text-center mb-2">Create Account</h2>
      <p class="text-slate-400 text-center text-sm mb-6">Join PawVerse to shop and book vet services</p>

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
          <label class="text-sm text-slate-400 mb-1 block">Full Name</label>
          <input type="text" name="name" required placeholder="Your full name" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
        </div>

        <div>
          <label class="text-sm text-slate-400 mb-1 block">Email</label>
          <input type="email" name="email" required placeholder="you@example.com" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
        </div>

        <div>
          <label class="text-sm text-slate-400 mb-1 block">Password</label>
          <input type="password" name="password" required placeholder="••••••••" minlength="6" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
        </div>

        <div>
          <label class="text-sm text-slate-400 mb-1 block">Confirm Password</label>
          <input type="password" name="confirm_password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
        </div>

        <div class="flex items-start gap-2 text-sm text-slate-400">
          <input type="checkbox" id="r_terms" name="terms" class="mt-1 accent-[color:var(--accent)]">
          <label for="r_terms">I agree to the <a href="#" class="text-[color:var(--accent)] hover:underline">Terms &amp; Conditions</a></label>
        </div>

        <button type="submit" class="w-full mt-4 bg-[color:var(--accent)] text-white py-3 rounded-lg font-semibold hover:bg-blue-500 transition">Register</button>
      </form>

      <p class="text-sm text-center text-slate-400 mt-6">
        Already have an account?
        <a href="login.php" class="text-[color:var(--accent)] hover:underline">Log in</a>
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
