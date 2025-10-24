<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Admin-only access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

// Add activity log
function add_activity_log($conn, $user_id, $action, $details = null) {
  $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action, details) VALUES (?, ?, ?)");
  if ($stmt) {
    $stmt->bind_param("iss", $user_id, $action, $details);
    $stmt->execute();
    $stmt->close();
  }
}

// Delete message
if (isset($_POST['confirm_delete'])) {
  $id = intval($_POST['delete_id']);
  $conn->query("DELETE FROM messages WHERE id = $id");
  add_activity_log($conn, $_SESSION['user_id'], 'Message deleted', "Message ID: {$id}");
  header("Location: messages.php");
  exit;
}

// Fetch messages with user info
$messages = $conn->query("
  SELECT m.id, m.subject, m.message, m.created_at, u.name, u.email
  FROM messages m
  LEFT JOIN users u ON m.user_id = u.id
  ORDER BY m.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>PawVerse Admin — Messages</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
  --bg:#f8fafc;--panel:#ffffff;--muted:#64748b;--text:#0f1724;--accent:#3b82f6;
  --glass-border:rgba(2,6,23,0.05);--card-shadow:0 6px 18px rgba(15,23,36,0.05);
}
.theme-dark {
  --bg:#0f1724;--panel:#1e293b;--muted:#94a3b8;--text:#e6eef8;
  --glass-border:rgba(255,255,255,0.06);--card-shadow:0 20px 40px rgba(2,6,23,0.5);
}
body {font-family:"Inter",sans-serif;background:var(--bg);color:var(--text);transition:background .35s,color .35s;}
.panel {background:var(--panel);border:1px solid var(--glass-border);box-shadow:var(--card-shadow);border-radius:.75rem;}
.muted {color:var(--muted);}
.btn{padding:.5rem 1rem;border-radius:.5rem;font-weight:500;}
.btn-primary{background:var(--accent);color:white;}
.btn-danger{background:#ef4444;color:white;}
.switch{width:52px;height:30px;border-radius:999px;padding:3px;display:inline-flex;align-items:center;cursor:pointer;background:rgba(0,0,0,0.08);}
.switch.on{background:linear-gradient(90deg,var(--accent),#60a5fa);}
.switch .knob{width:24px;height:24px;border-radius:999px;background:white;transform:translateX(0);transition:transform .25s;}
.switch.on .knob{transform:translateX(22px);}
th,td{text-align:left;padding:.75rem .75rem;white-space:nowrap;}
th:last-child,td:last-child{text-align:right;}
.modal-panel{background:var(--panel);border-radius:1rem;padding:1.5rem;width:100%;max-width:36rem;}
/* Input & textarea dark mode fix */
input, textarea, select {
  background-color: var(--panel);
  color: var(--text);
  border: 1px solid var(--glass-border);
}
input::placeholder, textarea::placeholder { color: var(--muted); opacity: 0.8; }
.theme-dark input, .theme-dark textarea, .theme-dark select {
  background-color: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.15);
  color: #e6eef8;
}
.theme-dark input::placeholder, .theme-dark textarea::placeholder { color: #94a3b8; }
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
          <a href="dashboard.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Dashboard</a>
          <a href="products.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Products</a>
          <a href="orders.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Orders</a>
          <a href="users.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Users</a>
          <a href="veterinarians.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Veterinarians</a>
          <a href="messages.php" class="block px-4 py-2 rounded-lg bg-[color:var(--accent)] text-white font-semibold">Messages</a>
        </nav>
      </div>
      <form method="POST" action="../auth/logout.php" class="pt-6">
        <button class="w-full py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition">Logout</button>
      </form>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="flex-1 ml-64 p-8">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">User Messages</h1>
      <div class="flex items-center gap-3">
        <div class="text-sm muted">☀️</div>
        <div id="themeSwitch" class="switch"><div class="knob"></div></div>
        <div class="text-sm muted">🌙</div>
      </div>
    </div>

    <!-- MESSAGES TABLE -->
    <div class="panel p-4 overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="border-b border-slate-200 dark:border-white/10 muted text-xs">
            <th>ID</th>
            <th>User</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($messages && $messages->num_rows > 0): while ($m = $messages->fetch_assoc()): ?>
          <tr class="border-b border-slate-100 dark:border-white/10">
            <td><?php echo $m['id']; ?></td>
            <td><?php echo htmlspecialchars($m['name'] ?? 'Unknown'); ?></td>
            <td><?php echo htmlspecialchars($m['email'] ?? '-'); ?></td>
            <td class="font-medium"><?php echo htmlspecialchars($m['subject']); ?></td>
            <td class="text-slate-500 dark:text-slate-300 max-w-md truncate" title="<?php echo htmlspecialchars($m['message']); ?>">
              <?php echo htmlspecialchars(substr($m['message'], 0, 60)) . (strlen($m['message']) > 60 ? '...' : ''); ?>
            </td>
            <td><?php echo date('M d, Y', strtotime($m['created_at'])); ?></td>
            <td class="text-right">
              <button onclick="viewMessage('<?php echo htmlspecialchars(addslashes($m['subject'])); ?>', '<?php echo htmlspecialchars(addslashes($m['message'])); ?>')" class="btn btn-primary text-xs">View</button>
              <button onclick="confirmDelete(<?php echo $m['id']; ?>, '<?php echo htmlspecialchars($m['subject']); ?>')" class="btn btn-danger text-xs ml-2">Delete</button>
            </td>
          </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="7" class="py-4 text-center muted">No messages found.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

    <footer class="mt-8 text-sm muted text-center">© <?php echo date('Y'); ?> PawVerse Admin Panel</footer>
  </main>
</div>

<!-- DELETE MODAL -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="modal-panel text-center">
    <form method="POST">
      <input type="hidden" name="confirm_delete" value="1">
      <input type="hidden" id="delete_id" name="delete_id">
      <h2 class="text-lg font-semibold mb-3">Delete Message</h2>
      <p class="muted mb-4" id="deleteText">Are you sure?</p>
      <div class="flex justify-center gap-3">
        <button type="button" onclick="toggleDelete(false)" class="btn bg-slate-300 text-slate-800">Cancel</button>
        <button type="submit" class="btn btn-danger">Delete</button>
      </div>
    </form>
  </div>
</div>

<!-- VIEW MESSAGE MODAL -->
<div id="viewModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="modal-panel max-w-lg">
    <h2 class="text-xl font-semibold mb-4" id="viewSubject">Message</h2>
    <p class="text-sm muted mb-6" id="viewMessage"></p>
    <div class="flex justify-end">
      <button onclick="toggleView(false)" class="btn btn-primary">Close</button>
    </div>
  </div>
</div>

<script>
function confirmDelete(id, subject){
  document.getElementById('delete_id').value=id;
  document.getElementById('deleteText').innerText=`Delete message "${subject}"?`;
  toggleDelete(true);
}
function toggleDelete(show){document.getElementById('deleteModal').classList.toggle('hidden',!show);}
function viewMessage(subject, message){
  document.getElementById('viewSubject').innerText=subject;
  document.getElementById('viewMessage').innerText=message;
  toggleView(true);
}
function toggleView(show){document.getElementById('viewModal').classList.toggle('hidden',!show);}
const switchEl=document.getElementById('themeSwitch'),body=document.body,saved=localStorage.getItem('pawverse_theme');
if(saved==='dark'){body.classList.add('theme-dark');switchEl.classList.add('on');}
switchEl.addEventListener('click',()=>{const isDark=body.classList.toggle('theme-dark');switchEl.classList.toggle('on');localStorage.setItem('pawverse_theme',isDark?'dark':'light');});
</script>
</body>
</html>
