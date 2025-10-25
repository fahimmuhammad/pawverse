<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

// ✅ Ensure activity_log table
$conn->query("
  CREATE TABLE IF NOT EXISTS activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(191) NOT NULL,
    details TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

function add_activity_log($conn, $user_id, $action, $details = null) {
  $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action, details) VALUES (?, ?, ?)");
  $stmt->bind_param("iss", $user_id, $action, $details);
  $stmt->execute();
  $stmt->close();
}

// ✅ Ensure columns exist
function column_exists($conn, $table, $column) {
  $db = $conn->query("SELECT DATABASE()")->fetch_row()[0];
  $q = $conn->prepare("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=? AND table_name=? AND column_name=?");
  $q->bind_param("sss", $db, $table, $column);
  $q->execute();
  $q->bind_result($c);
  $q->fetch();
  $q->close();
  return $c > 0;
}
if (!column_exists($conn, 'messages', 'sender_name')) $conn->query("ALTER TABLE messages ADD COLUMN sender_name VARCHAR(150) NULL AFTER user_id");
if (!column_exists($conn, 'messages', 'sender_email')) $conn->query("ALTER TABLE messages ADD COLUMN sender_email VARCHAR(255) NULL AFTER sender_name");
if (!column_exists($conn, 'messages', 'is_read')) $conn->query("ALTER TABLE messages ADD COLUMN is_read TINYINT(1) DEFAULT 0");
if (!column_exists($conn, 'messages', 'status')) $conn->query("ALTER TABLE messages ADD COLUMN status VARCHAR(50) DEFAULT 'unread'");

// ✅ Handle actions
$toast = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($_POST['action'] === 'toggle_read') {
    $id = intval($_POST['id']);
    $res = $conn->query("SELECT is_read FROM messages WHERE id=$id");
    if ($res && $row = $res->fetch_assoc()) {
      $new = $row['is_read'] ? 0 : 1;
      $status = $new ? 'read' : 'unread';
      $conn->query("UPDATE messages SET is_read=$new, status='$status' WHERE id=$id");
      add_activity_log($conn, $_SESSION['user_id'], 'Changed message status', "Message #$id marked $status");
      $toast = ['type' => 'success', 'text' => "Message marked $status."];
    }
  }
  elseif ($_POST['action'] === 'delete') {
    $id = intval($_POST['id']);
    $conn->query("DELETE FROM messages WHERE id=$id");
    add_activity_log($conn, $_SESSION['user_id'], 'Deleted message', "Message #$id deleted");
    $toast = ['type' => 'success', 'text' => "Message deleted."];
  }
  elseif ($_POST['action'] === 'clear_all' && isset($_POST['confirm']) && $_POST['confirm'] === '1') {
    $conn->query("TRUNCATE TABLE messages");
    add_activity_log($conn, $_SESSION['user_id'], 'Cleared all messages', "All messages removed");
    $toast = ['type' => 'success', 'text' => "All messages cleared."];
  }
}

// ✅ Fetch messages
$messages = $conn->query("SELECT * FROM messages ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);

// Helper for user info
function fetch_user($conn, $id) {
  $st = $conn->prepare("SELECT name,email FROM users WHERE id=?");
  $st->bind_param("i", $id);
  $st->execute();
  $r = $st->get_result()->fetch_assoc();
  $st->close();
  return $r ?: null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>PawVerse Admin — Messages</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
:root {
  --bg: #f8fafc; --panel:#ffffff; --text:#0f1724; --muted:#64748b; --accent:#3b82f6;
  --border:rgba(0,0,0,0.1);
}
.theme-dark {
  --bg:#0f1724; --panel:#1e293b; --text:#e2e8f0; --muted:#94a3b8; --accent:#3b82f6;
  --border:rgba(255,255,255,0.1);
}
body { background:var(--bg); color:var(--text); font-family:"Inter",sans-serif; }
.panel { background:var(--panel); border:1px solid var(--border); border-radius:0.75rem; box-shadow:0 4px 12px rgba(0,0,0,0.05); }
.muted { color:var(--muted); }
.badge { padding:3px 8px; border-radius:6px; font-weight:600; font-size:0.8rem; }
.badge-read { background:#10b981; color:white; }
.badge-unread { background:#f97316; color:white; }
.btn { padding:6px 10px; font-size:0.85rem; border-radius:6px; font-weight:600; transition:all .2s; }
.btn:hover { opacity:0.9; }
.btn-blue { background:var(--accent); color:white; }
.btn-gray { background:rgba(148,163,184,0.2); color:var(--text); }
.btn-red { background:#ef4444; color:white; }
.toast { position:fixed; top:1rem; left:50%; transform:translateX(-50%) translateY(-10px); opacity:0; transition:all .3s; padding:10px 16px; border-radius:6px; font-weight:600; }
.toast.show { opacity:1; transform:translateX(-50%) translateY(0); }
.toast-success { background:#10b981; color:white; }
.toast-warning { background:#f59e0b; color:white; }
.toast-error { background:#ef4444; color:white; }
.switch { width:50px; height:28px; border-radius:999px; padding:3px; display:inline-flex; align-items:center; cursor:pointer; background:rgba(0,0,0,0.1); }
.switch.on { background:linear-gradient(90deg,var(--accent),#60a5fa); }
.switch .knob { width:22px; height:22px; border-radius:999px; background:white; transform:translateX(0); transition:transform .25s; }
.switch.on .knob { transform:translateX(22px); }
</style>
</head>
<body>

<?php if ($toast): ?>
<div id="toast" class="toast toast-<?php echo $toast['type']; ?>"><?php echo htmlspecialchars($toast['text']); ?></div>
<script>setTimeout(()=>document.getElementById('toast').classList.add('show'),50); setTimeout(()=>{document.getElementById('toast').remove();},3500);</script>
<?php endif; ?>

<div class="min-h-screen flex">
  <!-- Sidebar -->
  <aside class="fixed top-0 left-0 h-full w-64 p-6 panel z-40 flex flex-col justify-between">
    <div>
      <div class="flex items-center gap-3 mb-8">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-[color:var(--accent)] text-white font-bold">PV</div>
        <div>
          <h3 class="text-lg font-bold">PawVerse Admin</h3>
          <div class="text-xs muted">Control panel</div>
        </div>
      </div>
      <nav class="space-y-2">
        <a href="dashboard.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10">Dashboard</a>
        <a href="products.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10">Products</a>
        <a href="orders.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10">Orders</a>
        <a href="users.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10">Users</a>
        <a href="vets.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10">Veterinarians</a>
        <a href="messages.php" class="block px-4 py-2 rounded-lg bg-[color:var(--accent)] text-white font-semibold">Messages</a>
      </nav>
    </div>
    <form action="../auth/logout.php" method="POST">
      <button class="w-full bg-red-500 hover:bg-red-600 py-2 text-white rounded-lg font-semibold">Logout</button>
    </form>
  </aside>

  <!-- Main -->
  <main class="flex-1 ml-64 p-8">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold">Messages</h1>
        <p class="muted text-sm">Guest & user messages — newest first</p>
      </div>
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
          <span class="text-sm">☀️</span>
          <div id="themeSwitch" class="switch"><div class="knob"></div></div>
          <span class="text-sm">🌙</span>
        </div>
        <div>
          <button id="clearAllBtn" class="btn btn-gray">Clear All</button>
          <form id="clearAllForm" method="POST" style="display:none;">
            <input type="hidden" name="action" value="clear_all">
            <input type="hidden" name="confirm" value="1">
          </form>
        </div>
      </div>
    </div>

    <section class="panel p-4">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="text-left border-b border-[color:var(--border)] muted text-xs">
            <th class="py-3">When</th>
            <th class="py-3">From</th>
            <th class="py-3">Subject</th>
            <th class="py-3">Message</th>
            <th class="py-3">Status</th>
            <th class="py-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($messages)): ?>
            <tr><td colspan="6" class="py-6 text-center muted">No messages yet.</td></tr>
          <?php else: foreach ($messages as $m):
            $sender = ['name'=>'Guest','email'=>'—'];
            if ($m['user_id']) $u = fetch_user($conn, $m['user_id']);
            if (!empty($u)) $sender = $u;
            else {
              if (!empty($m['sender_name'])) $sender['name']=$m['sender_name'];
              if (!empty($m['sender_email'])) $sender['email']=$m['sender_email'];
            }
            $is_read = intval($m['is_read']);
            $badge = $is_read ? '<span class="badge badge-read">Read</span>' : '<span class="badge badge-unread">Unread</span>';
          ?>
          <tr class="border-b border-[color:var(--border)]">
            <td class="py-3 muted text-xs"><?php echo date('M d, Y H:i', strtotime($m['created_at'])); ?></td>
            <td class="py-3"><div class="font-semibold"><?php echo htmlspecialchars($sender['name']); ?></div><div class="text-xs muted"><?php echo htmlspecialchars($sender['email']); ?></div></td>
            <td class="py-3 font-semibold"><?php echo htmlspecialchars($m['subject']); ?></td>
            <td class="py-3 text-sm"><?php echo nl2br(htmlspecialchars($m['message'])); ?></td>
            <td class="py-3"><?php echo $badge; ?></td>
            <td class="py-3 text-right space-x-2">
              <a href="mailto:<?php echo rawurlencode($sender['email']); ?>?subject=<?php echo rawurlencode('Re: '.$m['subject']); ?>" class="btn btn-blue">Reply</a>
              <form method="POST" class="inline-block">
                <input type="hidden" name="action" value="toggle_read">
                <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
                <button type="submit" class="btn btn-gray"><?php echo $is_read ? 'Mark Unread' : 'Mark Read'; ?></button>
              </form>
              <form method="POST" class="inline-block">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
                <button type="submit" class="btn btn-red">Delete</button>
              </form>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </section>

    <footer class="text-center text-sm muted mt-6 border-t border-[color:var(--border)] pt-4">
      © <?php echo date('Y'); ?> PawVerse Admin Panel
    </footer>
  </main>
</div>

<script>
// Theme toggle
const sw = document.getElementById('themeSwitch'), body=document.body;
const saved=localStorage.getItem('pawverse_theme');
if(saved==='dark'){body.classList.add('theme-dark');sw.classList.add('on');}
sw.onclick=()=>{const dark=body.classList.toggle('theme-dark');sw.classList.toggle('on');localStorage.setItem('pawverse_theme',dark?'dark':'light');};

// Clear All confirm toast
const btn=document.getElementById('clearAllBtn'), form=document.getElementById('clearAllForm');
let lastTap=0;
btn.onclick=e=>{
  e.preventDefault();
  const now=Date.now();
  if(now-lastTap<2500){form.submit();return;}
  lastTap=now;
  showToast('warning','Tap again to confirm clearing all messages');
};
function showToast(type,text){
  const t=document.createElement('div');
  t.className='toast toast-'+type;
  t.innerText=text;
  document.body.appendChild(t);
  setTimeout(()=>t.classList.add('show'),30);
  setTimeout(()=>{t.classList.remove('show');setTimeout(()=>t.remove(),300)},3000);
}
</script>
</body>
</html>
