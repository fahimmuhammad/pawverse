<?php
// admin/dashboard.php
// Single-file admin dashboard with Light (default) ↔ Dark-Glass theme toggle.
// No backend logic; static layout ready for integration.
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawVerse — Admin Dashboard</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Inter font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      /* Light theme (default) variables */
      --bg: #f8fafc;            /* page background */
      --panel: #ffffff;         /* cards / panels */
      --muted: #64748b;         /* muted text */
      --text: #0f1724;          /* main text */
      --accent: #3b82f6;        /* brand blue */
      --glass-border: rgba(2,6,23,0.04);
      --card-shadow: 0 8px 24px rgba(15,23,36,0.04);
    }

    /* Dark glass overrides when body has .theme-dark */
    .theme-dark {
      --bg: linear-gradient(135deg,#0f1724,#334155);
      --panel: rgba(255,255,255,0.04);
      --muted: #94a3b8;
      --text: #e6eef8;
      --accent: #3b82f6;
      --glass-border: rgba(255,255,255,0.06);
      --card-shadow: 0 20px 40px rgba(2,6,23,0.5);
    }

    /* Smooth transition for themeable properties */
    .theme-transition * {
      transition: background-color 350ms cubic-bezier(.2,.9,.25,1),
                  color 300ms cubic-bezier(.2,.9,.25,1),
                  border-color 300ms cubic-bezier(.2,.9,.25,1),
                  box-shadow 300ms cubic-bezier(.2,.9,.25,1);
    }

    html,body{ font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
    body { background: var(--bg); color: var(--text); }

    /* Glass / panel style */
    .panel {
      background: var(--panel);
      border: 1px solid var(--glass-border);
      box-shadow: var(--card-shadow);
      border-radius: .75rem;
    }

    /* small utilities */
    .muted { color: var(--muted); }
    .accent { color: var(--accent); }
    .accent-bg { background-color: var(--accent); color: white; }

    /* fade-up animation util */
    .fade-up { transform: translateY(12px); opacity: 0; transition: all .56s cubic-bezier(.16,.84,.24,1); }
    .fade-up.in-view { transform: translateY(0); opacity: 1; }

    /* responsive sidebar behavior */
    .sidebar { transition: transform .36s cubic-bezier(.2,.9,.25,1); }
    .sidebar.hidden { transform: translateX(-110%); }

    /* slider switch */
    .switch {
      width: 52px; height: 30px; border-radius: 999px; padding: 3px; display: inline-flex; align-items: center; cursor: pointer;
      background: rgba(0,0,0,0.06);
      transition: background 250ms ease;
    }
    .switch .knob {
      width: 24px; height: 24px; border-radius: 999px; background: white; transform: translateX(0); transition: transform 240ms cubic-bezier(.2,.9,.25,1), background 240ms;
      box-shadow: 0 4px 10px rgba(2,6,23,0.12);
    }
    .switch.on { background: linear-gradient(90deg,var(--accent), #60a5fa); }
    .switch.on .knob { transform: translateX(22px); background: white; }

    /* small scrollbar tweaks for carousel-like scrolls if used */
    .no-scrollbar::-webkit-scrollbar { height: 8px; }
    .no-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.12); border-radius: 999px; }

    /* ensure nice table layout in both themes */
    table { border-collapse: collapse; width: 100%; }
    th, td { padding: .75rem 0.75rem; }
  </style>
</head>
<body class="theme-transition">

  <!-- layout: sidebar + main -->
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
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/6 transition">Products</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/6 transition">Orders</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/6 transition">Users</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/6 transition">Veterinarians</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/6 transition">Messages</a>
          </nav>
        </div>

        <div>
          <button id="logoutBtn" class="w-full py-2 rounded-lg text-white bg-red-500 hover:bg-red-600 transition">Logout</button>
        </div>
      </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 md:ml-64 p-6 md:p-8">

      <!-- Top bar: title + toggle + profile -->
      <div class="flex items-center justify-between gap-4 mb-8">
        <div>
          <h1 class="text-2xl font-bold">Dashboard Overview</h1>
          <div class="text-sm muted">Welcome back, Admin</div>
        </div>

        <div class="flex items-center gap-4">
          <!-- theme toggle slider (top-right) -->
          <div class="flex items-center gap-3">
            <div class="text-sm muted">Light</div>

            <div id="themeSwitch" role="switch" aria-checked="false" tabindex="0" class="switch" title="Toggle dark mode">
              <div class="knob"></div>
            </div>

            <div class="text-sm muted">Dark</div>
          </div>

          <!-- profile block -->
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[color:var(--accent)] to-blue-400 flex items-center justify-center text-white">A</div>
            <div class="text-right">
              <div class="font-semibold">Admin</div>
              <div class="text-xs muted">admin@pawverse.com</div>
            </div>
          </div>
        </div>
      </div>

      <!-- stats -->
      <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="panel p-4 fade-up">
          <div class="muted text-sm">Total Users</div>
          <div class="text-2xl font-bold">1,248</div>
        </div>

        <div class="panel p-4 fade-up" data-delay="80">
          <div class="muted text-sm">Orders</div>
          <div class="text-2xl font-bold">654</div>
        </div>

        <div class="panel p-4 fade-up" data-delay="160">
          <div class="muted text-sm">Veterinarians</div>
          <div class="text-2xl font-bold">45</div>
        </div>

        <div class="panel p-4 fade-up" data-delay="240">
          <div class="muted text-sm">Messages</div>
          <div class="text-2xl font-bold">37</div>
        </div>
      </section>

      <!-- activity table -->
      <section class="panel p-4 fade-up" data-delay="300">
        <h3 class="font-semibold mb-4">Recent Activity</h3>
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead>
              <tr class="muted text-xs text-left border-b" style="border-color:var(--glass-border)">
                <th>User</th>
                <th>Action</th>
                <th>Date</th>
                <th class="text-right">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b" style="border-color:var(--glass-border)">
                <td class="py-3">John Doe</td>
                <td class="py-3">Placed an order</td>
                <td class="py-3">Oct 21, 2025</td>
                <td class="py-3 text-right"><span class="px-3 py-1 rounded-full text-sm" style="background:rgba(16,185,129,0.12);color:#10b981">Completed</span></td>
              </tr>
              <tr class="border-b" style="border-color:var(--glass-border)">
                <td class="py-3">Aisha Rahman</td>
                <td class="py-3">Booked appointment</td>
                <td class="py-3">Oct 20, 2025</td>
                <td class="py-3 text-right"><span class="px-3 py-1 rounded-full text-sm" style="background:rgba(234,179,8,0.12);color:#eab308">Pending</span></td>
              </tr>
              <tr class="border-b" style="border-color:var(--glass-border)">
                <td class="py-3">Imran Hossain</td>
                <td class="py-3">Message sent</td>
                <td class="py-3">Oct 18, 2025</td>
                <td class="py-3 text-right"><span class="px-3 py-1 rounded-full text-sm" style="background:rgba(59,130,246,0.12);color:#3b82f6">Replied</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- footer -->
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

    // sidebar toggle on small screens
    const sidebar = document.getElementById('sidebar');
    document.addEventListener('click', (e) => {
      // close sidebar if clicked outside on small screens (optional)
    });

    // THEME TOGGLE (slider-style)
    const switchEl = document.getElementById('themeSwitch');
    const body = document.body;

    // initialize theme based on localStorage (default: light)
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
      // small class to animate property changes
      body.classList.add('theme-transition');
      // remove the transition class after animation so instantaneous DOM changes later are not animated weirdly
      setTimeout(()=> body.classList.remove('theme-transition'), 420);
    }

    // read saved preference
    const saved = localStorage.getItem('pawverse_theme');
    applyTheme(saved === 'dark' ? 'dark' : 'light');

    // toggle handler
    function toggleTheme() {
      const isDark = body.classList.contains('theme-dark');
      const next = isDark ? 'light' : 'dark';
      applyTheme(next);
      localStorage.setItem('pawverse_theme', next);
    }

    // click & keyboard support
    switchEl.addEventListener('click', toggleTheme);
    switchEl.addEventListener('keydown', (e) => { if(e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggleTheme(); } });

    // accessibility: reflect initial state for screen readers
    switchEl.setAttribute('role','switch');
    switchEl.setAttribute('aria-label','Toggle dark mode');
  </script>
</body>
</html>
