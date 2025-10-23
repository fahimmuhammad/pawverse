<?php
session_start();
include('../config/db.php');

// If user is already logged in
if (isset($_SESSION['user_id'])) {
  header("Location: ../index.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $user['name'];
      $_SESSION['user_role'] = $user['role'];

      if ($user['role'] === 'admin') {
        header("Location: ../admin/dashboard.php");
      } else {
        header("Location: ../index.php");
      }
      exit;
    } else {
      echo "<script>alert('Invalid password!');</script>";
    }
  } else {
    echo "<script>alert('No account found with that email!');</script>";
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
    :root {
      --deep-1: #0f1724;
      --deep-2: #334155;
      --accent: #3b82f6;
      --bg-light: #f8fafc;
      --text-light: #0f1724;
      --text-dark: #e6eef8;
    }

    html, body {
      font-family: "Inter", sans-serif;
      transition: background 0.4s, color 0.4s;
    }

    body {
      background: var(--bg-light);
      color: var(--text-light);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    body.theme-dark {
      background: linear-gradient(135deg, var(--deep-1), var(--deep-2));
      color: var(--text-dark);
    }

    .card-glass {
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.15);
      backdrop-filter: blur(10px);
      transition: background 0.4s, color 0.4s;
    }

    body.theme-dark .card-glass {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
    }

    .switch {
      width: 50px; height: 28px; border-radius: 999px;
      padding: 3px; display: inline-flex; align-items: center;
      background: rgba(0,0,0,0.1); cursor: pointer;
      transition: background 0.3s ease;
    }

    .switch.on {
      background: linear-gradient(90deg, var(--accent), #60a5fa);
    }

    .switch .knob {
      width: 22px; height: 22px; border-radius: 50%;
      background: white; transform: translateX(0);
      transition: transform 0.3s ease;
    }

    .switch.on .knob {
      transform: translateX(22px);
    }
  </style>
</head>
<body>

  <!-- HEADER -->
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

  <!-- LOGIN FORM -->
  <main class="flex-1 flex items-center justify-center px-6">
    <div class="w-full max-w-md card-glass rounded-3xl p-8 shadow-2xl">
      <h2 class="text-2xl font-semibold text-center mb-2">Welcome Back</h2>
      <p class="text-slate-400 text-center text-sm mb-6">Log in to continue to your PawVerse account</p>

      <form method="POST" class="space-y-4">
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
    // Theme Toggle (global)
    const switchEl = document.getElementById('themeSwitch');
    const body = document.body;
    const savedTheme = localStorage.getItem('pawverse_theme');

    if (savedTheme === 'dark') {
      body.classList.add('theme-dark');
      switchEl.classList.add('on');
    }

    switchEl.addEventListener('click', () => {
      body.classList.toggle('theme-dark');
      const newTheme = body.classList.contains('theme-dark') ? 'dark' : 'light';
      switchEl.classList.toggle('on');
      localStorage.setItem('pawverse_theme', newTheme);
    });
  </script>

</body>
</html>
