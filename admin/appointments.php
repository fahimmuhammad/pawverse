<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Admin access control
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

// Ensure required tables exist
$conn->query("CREATE TABLE IF NOT EXISTS activity_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  action VARCHAR(191) NOT NULL,
  details TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (created_at), INDEX (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$conn->query("CREATE TABLE IF NOT EXISTS notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  message TEXT NOT NULL,
  is_read TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (user_id), INDEX (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

function add_activity_log($conn, $user_id, $action, $details = null) {
  $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action, details) VALUES (?, ?, ?)");
  $stmt->bind_param("iss", $user_id, $action, $details);
  $stmt->execute();
  $stmt->close();
}

function add_notification($conn, $user_id, $msg) {
  $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
  $stmt->bind_param("is", $user_id, $msg);
  $stmt->execute();
  $stmt->close();
}

// POST actions
$toast = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id'] ?? 0);
  $action = $_POST['action'] ?? '';

  // --- STATUS UPDATE ---
  if ($action === 'update_status') {
    $status = $_POST['status'] ?? 'Pending';
    $valid = ['Pending','Confirmed','Completed','Cancelled'];
    if (in_array($status, $valid)) {
      $conn->query("UPDATE appointments SET status='{$status}' WHERE id={$id}");
      $u = $conn->query("SELECT user_id FROM appointments WHERE id={$id}")->fetch_assoc();
      if ($u) add_notification($conn, $u['user_id'], "Your appointment #{$id} status updated to {$status}.");
      add_activity_log($conn, $_SESSION['user_id'], "Updated appointment status", "Appointment #{$id} → {$status}");
      $toast = "Status updated successfully.";
    }
  }

  // --- APPROVE RESCHEDULE ---
  if ($action === 'approve_request' || $action === 'reject_request') {
    $appointment = $conn->query("SELECT * FROM appointments WHERE id={$id}")->fetch_assoc();
    if ($appointment) {
      if ($action === 'approve_request') {
        $conn->query("
          UPDATE appointments 
          SET appointment_date = requested_date,
              appointment_time = requested_time,
              request_status = 'Approved',
              requested_date = NULL,
              requested_time = NULL
          WHERE id={$id}
        ");
        add_notification($conn, $appointment['user_id'], "Your reschedule request for appointment #{$id} has been approved.");
        add_activity_log($conn, $_SESSION['user_id'], "Approved reschedule request", "Appointment #{$id}");
        $toast = "Reschedule request approved.";
      } else {
        $conn->query("UPDATE appointments SET request_status = 'Rejected' WHERE id={$id}");
        add_notification($conn, $appointment['user_id'], "Your reschedule request for appointment #{$id} has been rejected.");
        add_activity_log($conn, $_SESSION['user_id'], "Rejected reschedule request", "Appointment #{$id}");
        $toast = "Reschedule request rejected.";
      }
    }
  }

  // --- EDIT NOTES ---
  if ($action === 'edit_notes') {
    $notes = $conn->real_escape_string(trim($_POST['notes'] ?? ''));
    $conn->query("UPDATE appointments SET notes='{$notes}' WHERE id={$id}");
    add_activity_log($conn, $_SESSION['user_id'], "Edited notes", "Appointment #{$id}");
    $toast = "Notes updated.";
  }

  // --- DELETE APPOINTMENT ---
  if ($action === 'delete') {
    $conn->query("DELETE FROM appointments WHERE id={$id}");
    add_activity_log($conn, $_SESSION['user_id'], "Deleted appointment", "#{$id}");
    $toast = "Appointment deleted.";
  }

  // --- CLEAR ALL ---
  if ($action === 'clear_all') {
    $conn->query("DELETE FROM appointments");
    add_activity_log($conn, $_SESSION['user_id'], "Cleared all appointments");
    $toast = "All appointments cleared.";
  }
}

// Fetch appointments
$q = "
  SELECT a.*, u.name AS user_name, u.email AS user_email, 
         v.name AS vet_name, v.specialization 
  FROM appointments a
  LEFT JOIN users u ON a.user_id = u.id
  LEFT JOIN veterinarians v ON a.vet_id = v.id
  ORDER BY a.created_at DESC
";
$res = $conn->query($q);
$appointments = $res && $res->num_rows ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>PawVerse Admin — Appointments</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
  --bg:#f8fafc;--panel:#ffffff;--muted:#64748b;--text:#0f1724;--accent:#3b82f6;
  --glass-border:rgba(2,6,23,0.05);--card-shadow:0 8px 20px rgba(15,23,36,0.05);
}
.theme-dark {
  --bg:#0f1724;--panel:#1e293b;--muted:#94a3b8;--text:#e6eef8;
  --glass-border:rgba(255,255,255,0.06);--card-shadow:0 20px 40px rgba(2,6,23,0.5);
}
body {font-family:"Inter",sans-serif;background:var(--bg);color:var(--text);}
.panel {background:var(--panel);border:1px solid var(--glass-border);box-shadow:var(--card-shadow);border-radius:.75rem;}
.badge{padding:.25rem .6rem;border-radius:.4rem;font-weight:600;font-size:.75rem;}
.badge.Pending{background:#facc15;color:#000;}
.badge.Confirmed{background:#3b82f6;color:#fff;}
.badge.Completed{background:#16a34a;color:#fff;}
.badge.Cancelled{background:#ef4444;color:#fff;}
.badge.Approved{background:#22c55e;color:#fff;}
.badge.Rejected{background:#ef4444;color:#fff;}
.badge.PendingReq{background:#f59e0b;color:#fff;}
.switch {width:52px;height:30px;border-radius:999px;padding:3px;display:inline-flex;align-items:center;cursor:pointer;background:rgba(0,0,0,0.08);}
.switch.on {background:linear-gradient(90deg,var(--accent),#60a5fa);}
.switch .knob {width:24px;height:24px;border-radius:999px;background:white;transform:translateX(0);transition:transform .25s;}
.switch.on .knob {transform:translateX(22px);}
select {background-color: var(--panel);color: var(--text);border: 1px solid var(--glass-border);border-radius:.5rem;padding:.4rem .6rem;}
.theme-dark select {background-color:#334155;color:#f1f5f9;border-color:rgba(255,255,255,0.1);}
.toast{position:fixed;top:20px;left:50%;transform:translateX(-50%);background:#16a34a;color:white;padding:.75rem 1rem;border-radius:.5rem;font-weight:600;opacity:0;transition:opacity .3s;}
.toast.show{opacity:1;}
</style>
</head>
<body>

<?php if($toast): ?>
<div id="toast" class="toast"><?php echo htmlspecialchars($toast); ?></div>
<script>setTimeout(()=>document.getElementById('toast').classList.add('show'),100);setTimeout(()=>document.getElementById('toast').remove(),3000);</script>
<?php endif; ?>

<div class="min-h-screen flex">
  <!-- SIDEBAR -->
  <aside class="fixed top-0 left-0 h-full w-64 p-6 panel z-40">
    <div class="flex flex-col justify-between h-full">
      <div>
        <div class="flex items-center gap-3 mb-8">
          <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-[color:var(--accent)] text-white font-bold">PV</div>
          <div><h3 class="text-lg font-bold">PawVerse Admin</h3><div class="text-xs muted">Control panel</div></div>
        </div>
        <nav class="space-y-2">
          <a href="dashboard.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Dashboard</a>
          <a href="products.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Products</a>
          <a href="orders.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Orders</a>
          <a href="users.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Users</a>
          <a href="veterinarians.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Veterinarians</a>
          <a href="messages.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Messages</a>
          <a href="appointments.php" class="block px-4 py-2 rounded-lg bg-[color:var(--accent)] text-white font-semibold">Appointments</a>
        </nav>
      </div>
      <form method="POST" action="../auth/logout.php" class="pt-6">
        <button class="w-full py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition">Logout</button>
      </form>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="flex-1 ml-64 p-8">
    <h1 class="text-2xl font-bold mb-6">Manage Appointments</h1>

    <section class="panel p-4 overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="muted text-xs border-b">
            <th>ID</th><th>User</th><th>Veterinarian</th><th>Date</th><th>Status</th><th>Reschedule</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if(empty($appointments)): ?>
          <tr><td colspan="7" class="text-center muted py-6">No appointments found.</td></tr>
        <?php else: foreach($appointments as $a): ?>
          <tr class="border-b">
            <td>#<?php echo $a['id']; ?></td>
            <td><?php echo htmlspecialchars($a['user_name']); ?><br><span class="text-xs muted"><?php echo htmlspecialchars($a['user_email']); ?></span></td>
            <td><?php echo htmlspecialchars($a['vet_name']); ?><br><span class="text-xs muted"><?php echo htmlspecialchars($a['specialization']); ?></span></td>
            <td><?php echo date('M d, Y', strtotime($a['appointment_date'])); ?> @ <?php echo $a['appointment_time']; ?></td>
            <td><span class="badge <?php echo $a['status']; ?>"><?php echo $a['status']; ?></span></td>
            <td>
              <?php if($a['request_status'] && $a['request_status']!=='None'): ?>
                <div>
                  <span class="badge <?php echo $a['request_status']; ?>"><?php echo $a['request_status']; ?></span><br>
                  <small><?php echo htmlspecialchars($a['requested_date']); ?> @ <?php echo htmlspecialchars($a['requested_time']); ?></small>
                  <?php if($a['request_status']==='Pending'): ?>
                    <form method="POST" class="mt-2 flex gap-2">
                      <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                      <button name="action" value="approve_request" class="px-2 py-1 bg-green-500 text-white rounded text-xs hover:bg-green-600">Approve</button>
                      <button name="action" value="reject_request" class="px-2 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600">Reject</button>
                    </form>
                  <?php endif; ?>
                </div>
              <?php else: ?><span class="text-xs muted">—</span><?php endif; ?>
            </td>
            <td>
              <form method="POST" style="display:inline;">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                <select name="status" onchange="this.form.submit()">
                  <?php foreach(['Pending','Confirmed','Completed','Cancelled'] as $s): ?>
                    <option value="<?php echo $s; ?>" <?php if($s==$a['status']) echo 'selected'; ?>><?php echo $s; ?></option>
                  <?php endforeach; ?>
                </select>
              </form>
            </td>
          </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </section>

    <footer class="mt-8 text-sm muted text-center border-t border-slate-200 dark:border-white/10 pt-6">
      © <?php echo date('Y'); ?> PawVerse Admin Panel
    </footer>
  </main>
</div>

<script>
const switchEl=document.getElementById('themeSwitch'),body=document.body,saved=localStorage.getItem('pawverse_theme');
if(saved==='dark'){body.classList.add('theme-dark');switchEl?.classList.add('on');}
switchEl?.addEventListener('click',()=>{const d=body.classList.toggle('theme-dark');switchEl.classList.toggle('on');localStorage.setItem('pawverse_theme',d?'dark':'light');});
</script>
</body>
</html>
