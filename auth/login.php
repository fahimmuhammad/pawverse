<?php
session_start();
include __DIR__ . '/../config/db.php';

if (isset($_SESSION['user_id'])) {
  echo "<script>alert('You are already logged in! Redirecting...');window.location.href='../index.php';</script>";
  exit;
}


if (isset($_SESSION['user_role'])) {
  header('Location: ' . ($_SESSION['user_role'] === 'admin' ? '../admin/dashboard.php' : '../index.php'));
  exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($email === '' || $password === '') {
    $error = 'Please fill in all fields.';
  } else {
    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows === 1) {
      $user = $res->fetch_assoc();
      if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        header('Location: ' . ($user['role'] === 'admin' ? '../admin/dashboard.php' : '../index.php'));
        exit;
      } else $error = 'Invalid password.';
    } else $error = 'No account found with this email.';
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
<script src="../assets/js/theme.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<style>
:root{
  --page-bg: linear-gradient(to bottom, #f8fafc, #e6eef8);
  --panel-bg: #ffffff;
  --muted: #64748b;
  --text: #0f1724;
  --accent: #3b82f6;
  --glass-border: rgba(2,6,23,0.04);
  --input-bg: #f1f5f9;
  --input-border: #cbd5e1;
}
.theme-dark {
  --page-bg: linear-gradient(180deg,#0f1724,#334155);
  --panel-bg: rgba(255,255,255,0.04);
  --muted: #94a3b8;
  --text: #e6eef8;
  --accent: #3b82f6;
  --glass-border: rgba(255,255,255,0.06);
  --input-bg: rgba(255,255,255,0.06);
  --input-border: rgba(255,255,255,0.08);
}
html,body{font-family:"Inter",system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial;}
body{background:var(--page-bg);color:var(--text);display:flex;flex-direction:column;min-height:100vh;}
.card-glass{background:var(--panel-bg);border:1px solid var(--glass-border);backdrop-filter:blur(10px);}
.form-input{background:var(--input-bg);border:1px solid var(--input-border);color:var(--text);}
.fade-up{transform:translateY(18px);opacity:0;transition:all .56s cubic-bezier(.16,.84,.24,1);}
.fade-up.in-view{transform:translateY(0);opacity:1;}
/* Toggle Switch */
.switch{width:50px;height:28px;border-radius:999px;background:rgba(0,0,0,0.1);padding:3px;display:inline-flex;align-items:center;cursor:pointer;transition:background .3s;}
.switch.on{background:linear-gradient(90deg,var(--accent),#60a5fa);}
.switch .knob{width:22px;height:22px;border-radius:999px;background:white;transition:transform .3s;}
.switch.on .knob{transform:translateX(22px);}
</style>
</head>

<body class="antialiased">

<!-- HEADER -->
<header class="w-full py-5 relative flex items-center justify-center">
  <!-- Centered logo -->
  <a href="../index.php" class="font-bold tracking-tight text-2xl hover:underline" style="color:var(--accent)">🐾 PawVerse</a>
  <!-- Toggle fixed top-right -->
  <div class="absolute right-6 top-1/2 -translate-y-1/2 flex items-center gap-2">
    <div class="text-sm" style="color:var(--muted)">☀️</div>
    <div id="themeSwitch" role="switch" aria-checked="false" tabindex="0" class="switch"><div class="knob"></div></div>
    <div class="text-sm" style="color:var(--muted)">🌙</div>
  </div>
</header>

<!-- LOGIN FORM -->
<main class="flex-1 flex items-center justify-center px-6">
  <div class="w-full max-w-md card-glass rounded-3xl p-8 fade-up shadow-2xl">
    <h2 class="text-2xl font-semibold text-center mb-2">Welcome back</h2>
    <p class="text-sm text-center mb-4" style="color:var(--muted)">Log in to continue to your PawVerse account</p>

    <?php if ($error): ?>
      <div class="bg-red-600 text-white text-sm px-4 py-2 rounded-lg mb-4 text-center"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="text-sm block" style="color:var(--muted)">Email</label>
        <input type="email" name="email" required placeholder="you@example.com" class="w-full px-4 py-3 rounded-lg form-input focus:outline-none focus:ring-2">
      </div>
      <div>
        <label class="text-sm block" style="color:var(--muted)">Password</label>
        <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-lg form-input focus:outline-none focus:ring-2">
      </div>
      <div class="flex items-center justify-between text-sm">
        <label class="flex items-center gap-2" style="color:var(--muted)">
          <input type="checkbox" class="accent-[color:var(--accent)]"> Remember me
        </label>
        <a href="#" style="color:var(--accent)" class="hover:underline">Forgot password?</a>
      </div>
      <button type="submit" class="w-full mt-4" style="background:var(--accent);color:white;padding:.75rem 1rem;border-radius:.75rem;font-weight:600;">Login</button>
    </form>

    <p class="text-sm text-center mt-6" style="color:var(--muted)">
      Don’t have an account? <a href="register.php" style="color:var(--accent)" class="hover:underline">Create one</a>
    </p>
  </div>
</main>

<footer class="text-center text-sm py-4" style="color:var(--muted);border-top:1px solid var(--glass-border)">© <?php echo date('Y'); ?> PawVerse. All rights reserved.</footer>

<script>
// Fade-in
const observer=new IntersectionObserver(e=>e.forEach(x=>x.isIntersecting&&x.target.classList.add('in-view')),{threshold:.08});
document.querySelectorAll('.fade-up').forEach(el=>observer.observe(el));

// Make sure toggle connects with global theme.js
document.addEventListener("DOMContentLoaded",()=>{
  const themeSwitch=document.getElementById("themeSwitch");
  if(localStorage.getItem("pawverse_theme")==="dark"){
    document.body.classList.add("theme-dark");
    themeSwitch.classList.add("on");
  }
  themeSwitch.addEventListener("click",()=>{
    const dark=document.body.classList.toggle("theme-dark");
    themeSwitch.classList.toggle("on",dark);
    localStorage.setItem("pawverse_theme",dark?"dark":"light");
  });
});
</script>
</body>
</html>
