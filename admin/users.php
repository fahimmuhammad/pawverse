<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Admin-only access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

function add_activity_log($conn, $user_id, $action, $details = null) {
  $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action, details) VALUES (?, ?, ?)");
  if ($stmt) {
    $stmt->bind_param("iss", $user_id, $action, $details);
    $stmt->execute();
    $stmt->close();
  }
}

function get_client_ip() {
  foreach (['HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'] as $key) {
    if (!empty($_SERVER[$key])) {
      $ip = explode(',', $_SERVER[$key])[0];
      return $ip === '::1' ? '127.0.0.1' : $ip;
    }
  }
  return 'unknown';
}
$client_ip = get_client_ip();

// Add user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
  $role = $_POST['role'];
  $status = $_POST['status'];

  $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $name, $email, $password, $role, $status);
  if ($stmt->execute()) {
    add_activity_log($conn, $_SESSION['user_id'], 'User added', "Added user: {$name} ({$email}), IP: {$client_ip}");
  }
  $stmt->close();
  header("Location: users.php");
  exit;
}

// Delete user
if (isset($_POST['confirm_delete'])) {
  $id = intval($_POST['delete_id']);
  $res = $conn->query("SELECT role FROM users WHERE id = $id");
  $role = $res && $res->num_rows ? $res->fetch_assoc()['role'] : '';
  if ($role !== 'admin') {
    $conn->query("DELETE FROM users WHERE id = $id");
    add_activity_log($conn, $_SESSION['user_id'], 'User deleted', "Deleted user ID: {$id}, IP: {$client_ip}");
  }
  header("Location: users.php");
  exit;
}

// Toggle status
if (isset($_POST['toggle_status'])) {
  $id = intval($_POST['user_id']);
  $res = $conn->query("SELECT role, status FROM users WHERE id = $id");
  if ($res && $res->num_rows > 0) {
    $user = $res->fetch_assoc();
    if ($user['role'] !== 'admin') {
      $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
      $conn->query("UPDATE users SET status='$newStatus' WHERE id=$id");
      add_activity_log($conn, $_SESSION['user_id'], 'User status changed', "User ID: {$id} set to {$newStatus}, IP: {$client_ip}");
    }
  }
  header("Location: users.php");
  exit;
}

$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>PawVerse Admin — Manage Users</title>
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
/* FIXED: dark mode input & select visibility */
input, textarea, select {
  background-color: var(--panel);
  color: var(--text);
  border: 1px solid var(--glass-border);
  transition: all .25s ease;
}
input::placeholder, textarea::placeholder {
  color: var(--muted);
  opacity: 0.8;
}
.theme-dark input, 
.theme-dark textarea, 
.theme-dark select {
  background-color: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.15);
  color: #e6eef8;
}
.theme-dark input::placeholder, 
.theme-dark textarea::placeholder {
  color: #94a3b8;
}
.theme-dark select option {
  background-color: #1e293b;
  color: #e6eef8;
}
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
          <a href="users.php" class="block px-4 py-2 rounded-lg bg-[color:var(--accent)] text-white font-semibold">Users</a>
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
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Manage Users</h1>
      <div class="flex items-center gap-3">
        <div class="text-sm muted">☀️</div>
        <div id="themeSwitch" class="switch"><div class="knob"></div></div>
        <div class="text-sm muted">🌙</div>
        <button onclick="toggleModal(true)" class="btn btn-primary ml-4">+ Add User</button>
      </div>
    </div>

    <!-- USER TABLE -->
    <div class="panel p-4 overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="border-b border-slate-200 dark:border-white/10 muted text-xs">
            <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Created</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($users->num_rows > 0): while ($u = $users->fetch_assoc()): ?>
          <tr class="border-b border-slate-100 dark:border-white/10">
            <td><?php echo $u['id']; ?></td>
            <td><?php echo htmlspecialchars($u['name']); ?></td>
            <td><?php echo htmlspecialchars($u['email']); ?></td>
            <td class="capitalize"><?php echo htmlspecialchars($u['role']); ?></td>
            <td>
              <?php if ($u['role'] !== 'admin'): ?>
              <form method="POST" style="display:inline;">
                <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                <button type="submit" name="toggle_status"
                        class="px-2 py-1 rounded text-xs font-semibold <?php echo $u['status']==='active'?'bg-green-500/20 text-green-400':'bg-yellow-500/20 text-yellow-300'; ?>">
                  <?php echo ucfirst($u['status']); ?>
                </button>
              </form>
              <?php else: ?>
                <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded text-xs font-semibold">Active</span>
              <?php endif; ?>
            </td>
            <td><?php echo date('M d, Y', strtotime($u['created_at'])); ?></td>
            <td class="text-right">
              <?php if ($u['role'] !== 'admin'): ?>
              <button onclick="confirmDelete(<?php echo $u['id']; ?>, '<?php echo htmlspecialchars($u['name']); ?>')" class="btn btn-danger text-xs">Delete</button>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="7" class="py-4 text-center muted">No users found.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

    <footer class="mt-8 text-sm muted text-center">© <?php echo date('Y'); ?> PawVerse Admin Panel</footer>
  </main>
</div>

<!-- ADD USER MODAL -->
<div id="addModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="modal-panel">
    <h2 class="text-xl font-semibold mb-4">Add New User</h2>
    <form method="POST">
      <input type="hidden" name="add_user" value="1">
      <div class="mb-3"><label>Name</label><input name="name" required placeholder="Full name" class="w-full p-2 rounded"></div>
      <div class="mb-3"><label>Email</label><input type="email" name="email" required placeholder="you@example.com" class="w-full p-2 rounded"></div>
      <div class="mb-3"><label>Password</label><input type="password" name="password" required placeholder="••••••••" class="w-full p-2 rounded"></div>
      <div class="mb-3"><label>Role</label>
        <select name="role" class="w-full p-2 rounded">
          <option value="customer">Customer</option>
          <option value="vet">Vet</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div class="mb-3"><label>Status</label>
        <select name="status" class="w-full p-2 rounded">
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="toggleModal(false)" class="btn bg-slate-300 text-slate-800">Cancel</button>
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
    </form>
  </div>
</div>

<!-- DELETE MODAL -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="modal-panel text-center">
    <form method="POST">
      <input type="hidden" name="confirm_delete" value="1">
      <input type="hidden" id="delete_id" name="delete_id">
      <h2 class="text-lg font-semibold mb-3">Delete User</h2>
      <p class="muted mb-4" id="deleteText">Are you sure?</p>
      <div class="flex justify-center gap-3">
        <button type="button" onclick="toggleDelete(false)" class="btn bg-slate-300 text-slate-800">Cancel</button>
        <button type="submit" class="btn btn-danger">Delete</button>
      </div>
    </form>
  </div>
</div>

<script>
function toggleModal(show){document.getElementById('addModal').classList.toggle('hidden',!show);}
function confirmDelete(id,name){
  document.getElementById('delete_id').value=id;
  document.getElementById('deleteText').innerText=`Are you sure you want to delete "${name}"?`;
  toggleDelete(true);
}
function toggleDelete(show){document.getElementById('deleteModal').classList.toggle('hidden',!show);}
const switchEl=document.getElementById('themeSwitch'),body=document.body,saved=localStorage.getItem('pawverse_theme');
if(saved==='dark'){body.classList.add('theme-dark');switchEl.classList.add('on');}
switchEl.addEventListener('click',()=>{const isDark=body.classList.toggle('theme-dark');switchEl.classList.toggle('on');localStorage.setItem('pawverse_theme',isDark?'dark':'light');});
</script>
</body>
</html>
