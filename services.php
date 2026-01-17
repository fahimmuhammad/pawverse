<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include(__DIR__ . '/config/db.php');

// Check login state
$isLoggedIn = isset($_SESSION['user_id']);
$username = '';
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;

if ($isLoggedIn) {
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();
}

// Handle booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_appointment'])) {
    if (!$isLoggedIn) {
        header("Location: auth/login.php");
        exit;
    }
    $vet_id = intval($_POST['vet_id']);
    $pet_name = trim($_POST['pet_name']);
    $pet_type = trim($_POST['pet_type']);
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $notes = trim($_POST['notes']);

    if ($vet_id && $appointment_date && $appointment_time) {
        $stmt = $conn->prepare("INSERT INTO appointments (user_id, vet_id, pet_name, pet_type, appointment_date, appointment_time, notes, status)
                                VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("iisssss", $userId, $vet_id, $pet_name, $pet_type, $appointment_date, $appointment_time, $notes);
        $stmt->execute();
        $stmt->close();
        $success = "Appointment booked successfully!";
    }
}

// Handle cancel
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_id'])) {
    $cancelId = intval($_POST['cancel_id']);
    $stmt = $conn->prepare("UPDATE appointments SET status='Cancelled' WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $cancelId, $userId);
    $stmt->execute();
    $stmt->close();
    $success = "Appointment #$cancelId cancelled successfully.";
}

// Handle reschedule request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reschedule_id'])) {
    $id = intval($_POST['reschedule_id']);
    $new_date = $_POST['new_date'];
    $new_time = $_POST['new_time'];

    if (!empty($new_date) && !empty($new_time)) {
        $stmt = $conn->prepare("UPDATE appointments SET requested_date=?, requested_time=?, request_status='Pending' WHERE id=? AND user_id=?");
        $stmt->bind_param("ssii", $new_date, $new_time, $id, $userId);
        $stmt->execute();
        $stmt->close();
        $success = "Requested change for appointment #$id. Awaiting admin approval.";
    }
}

// Fetch vets
$vets = [];
$result = $conn->query("SELECT id, name, specialization, description, image, fee, rating, experience_years, phone FROM veterinarians ORDER BY id ASC");
if ($result && $result->num_rows > 0) while ($row = $result->fetch_assoc()) $vets[] = $row;

// Fetch user's appointments (if logged in)
$appointments = [];
if ($isLoggedIn) {
    $stmt = $conn->prepare("
      SELECT a.*, v.name AS vet_name, v.specialization, v.fee, v.image
      FROM appointments a
      LEFT JOIN veterinarians v ON a.vet_id = v.id
      WHERE a.user_id = ?
      ORDER BY a.appointment_date DESC
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    $appointments = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>PawVerse — Services</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
  --deep-1:#ffffff;--deep-2:#f8fafc;--cream:#ffffff;
  --accent:#3b82f6;--text:#0f172a;--panel:#ffffff;--soft:#f1f5f9;
}
body.theme-dark {
  --deep-1:#0f1724;--deep-2:#334155;--cream:rgba(255,255,255,0.03);
  --accent:#3b82f6;--text:#e6eef8;--panel:rgba(255,255,255,0.05);
  --soft:rgba(255,255,255,0.04);
  background:linear-gradient(135deg,var(--deep-1),var(--deep-2));color:var(--text);
}
.switch{width:52px;height:30px;border-radius:999px;padding:3px;display:inline-flex;align-items:center;cursor:pointer;background:rgba(0,0,0,0.1);}
.switch.on{background:linear-gradient(90deg,var(--accent),#60a5fa);}
.switch .knob{width:24px;height:24px;border-radius:999px;background:white;transform:translateX(0);transition:transform .25s;}
.switch.on .knob{transform:translateX(22px);}
.panel{background:var(--panel);border-radius:1rem;padding:1.5rem;box-shadow:0 8px 20px rgba(15,23,36,0.08);}
.status{padding:.3rem .6rem;border-radius:.4rem;font-weight:600;font-size:.75rem;}
.status.Pending{background:#facc15;color:#000;}
.status.Confirmed{background:#3b82f6;color:#fff;}
.status.Completed{background:#16a34a;color:#fff;}
.status.Cancelled{background:#ef4444;color:#fff;}
.status.Requested{background:#22c55e;color:#fff;}
body.theme-dark .bg-green-500{background-color:#22c55e!important;}
body.theme-dark .bg-green-500:hover{background-color:#16a34a!important;}
</style>
</head>

<body class="bg-[color:var(--cream)] text-[color:var(--text)] antialiased">

<!-- Nav -->
<?php include('includes/header.php'); ?>

<!-- MAIN -->
<main class="pt-28">
  <section class="max-w-7xl mx-auto px-6 py-10">
    <h3 class="text-3xl font-bold mb-8 text-[color:var(--accent)]">Meet Our Veterinarians</h3>

    <?php if (isset($success)): ?>
      <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg mb-4"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php foreach($vets as $v): ?>
        <article class="panel">
          <img src="assets/images/<?php echo htmlspecialchars($v['image']); ?>" alt="<?php echo htmlspecialchars($v['name']); ?>" class="w-full h-52 object-contain rounded-lg mb-4">
          <h4 class="font-semibold text-lg"><?php echo htmlspecialchars($v['name']); ?></h4>
          <p class="text-sm opacity-80 mb-2"><?php echo htmlspecialchars($v['specialization']); ?> Specialist</p>
          <p class="text-sm opacity-70 mb-4"><?php echo htmlspecialchars($v['description']); ?></p>
          <div class="text-sm opacity-70 mb-3">Experience: <?php echo htmlspecialchars($v['experience_years']); ?> years</div>
          <div class="text-sm opacity-70 mb-3">📞 <?php echo htmlspecialchars($v['phone']); ?></div>
          <div class="flex items-center justify-between mt-3">
            <div class="text-[color:var(--accent)] font-semibold">Fee: $<?php echo htmlspecialchars($v['fee']); ?></div>
            <?php if ($isLoggedIn): ?>
              <button onclick="openBookingModal(<?php echo $v['id']; ?>, '<?php echo htmlspecialchars($v['name']); ?>')" class="bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white hover:bg-blue-600 transition">Book</button>
            <?php else: ?>
              <a href="auth/login.php" class="bg-[color:var(--accent)] px-4 py-2 rounded-lg text-white hover:bg-blue-600 transition">Book</a>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- MY APPOINTMENTS SECTION -->
  <?php if ($isLoggedIn): ?>
  <section id="myAppointments" class="max-w-7xl mx-auto px-6 py-12 border-t border-white/10 mt-8">
    <h3 class="text-2xl font-bold mb-6 text-[color:var(--accent)]">My Appointments</h3>

    <?php if (empty($appointments)): ?>
      <p class="opacity-70">You haven’t booked any appointments yet.</p>
    <?php else: ?>
      <div class="grid md:grid-cols-2 gap-6">
        <?php foreach ($appointments as $a): ?>
          <div class="panel">
            <div class="flex items-center gap-4 mb-3">
              <img src="assets/images/<?php echo htmlspecialchars($a['image'] ?? 'vet1.png'); ?>" class="w-16 h-16 object-cover rounded-lg border" alt="">
              <div>
                <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($a['vet_name']); ?></h3>
                <p class="text-sm opacity-80"><?php echo htmlspecialchars($a['specialization']); ?> • $<?php echo htmlspecialchars($a['fee']); ?></p>
              </div>
            </div>
            <div class="text-sm opacity-80 mb-1">🐾 Pet: <?php echo htmlspecialchars($a['pet_name']); ?> (<?php echo htmlspecialchars($a['pet_type']); ?>)</div>
            <div class="text-sm opacity-80 mb-1">📅 <?php echo date('M d, Y', strtotime($a['appointment_date'])); ?> at <?php echo htmlspecialchars($a['appointment_time']); ?></div>
            <?php if ($a['request_status'] !== 'None'): ?>
              <div class="text-sm opacity-80 mb-2">
                🔁 Requested change: <?php echo htmlspecialchars($a['requested_date']); ?> at <?php echo htmlspecialchars($a['requested_time']); ?> 
                (<strong><?php echo htmlspecialchars($a['request_status']); ?></strong>)
              </div>
            <?php endif; ?>
            <div class="flex items-center justify-between mt-3">
              <span class="status <?php echo $a['status']; ?>"><?php echo $a['status']; ?></span>
              <div class="flex gap-3">
                <button onclick="openRescheduleModal(<?php echo $a['id']; ?>)" class="text-blue-600 hover:underline text-sm font-semibold">Request Change</button>
                <form method="POST" onsubmit="return confirm('Cancel this appointment?');">
                  <input type="hidden" name="cancel_id" value="<?php echo $a['id']; ?>">
                  <button class="text-red-600 hover:underline text-sm font-semibold">Cancel</button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
  <?php endif; ?>
</main>

<!-- BOOKING MODAL -->
<div id="bookingModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
  <div class="bg-[color:var(--panel)] p-6 rounded-2xl max-w-md w-full mx-4 shadow-xl relative">
    <button onclick="closeBookingModal()" class="absolute top-3 right-3 text-[color:var(--text)] hover:text-red-500">✕</button>
    <h3 class="text-xl font-semibold mb-4">Book Appointment</h3>
    <form method="POST">
      <input type="hidden" name="book_appointment" value="1">
      <input type="hidden" name="vet_id" id="vet_id">
      <div class="mb-3">
        <label class="block text-sm font-medium mb-1">Veterinarian</label>
        <input type="text" id="vet_name" readonly class="w-full px-3 py-2 rounded-lg border bg-gray-50">
      </div>
      <div class="grid grid-cols-2 gap-3 mb-3">
        <input name="pet_name" placeholder="Pet name" required class="px-3 py-2 rounded-lg border">
        <input name="pet_type" placeholder="Pet type" required class="px-3 py-2 rounded-lg border">
      </div>
      <div class="grid grid-cols-2 gap-3 mb-3">
        <input type="date" name="appointment_date" required class="px-3 py-2 rounded-lg border">
        <input type="time" name="appointment_time" required class="px-3 py-2 rounded-lg border">
      </div>
      <textarea name="notes" rows="3" placeholder="Notes (optional)" class="w-full px-3 py-2 rounded-lg border mb-3"></textarea>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeBookingModal()" class="px-4 py-2 rounded-lg border">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-[color:var(--accent)] text-white rounded-lg">Confirm</button>
      </div>
    </form>
  </div>
</div>

<!-- RESCHEDULE MODAL -->
<div id="rescheduleModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
  <div class="bg-[color:var(--panel)] p-6 rounded-2xl max-w-sm w-full mx-4 relative">
    <button onclick="closeRescheduleModal()" class="absolute top-3 right-3 text-[color:var(--text)] hover:text-red-500">✕</button>
    <h3 class="text-xl font-semibold mb-4">Request Schedule Change</h3>
    <form method="POST">
      <input type="hidden" name="reschedule_id" id="reschedule_id">
      <label class="block text-sm mb-1">New Date</label>
      <input type="date" name="new_date" required class="w-full px-3 py-2 border rounded-lg mb-3">
      <label class="block text-sm mb-1">New Time</label>
      <input type="time" name="new_time" required class="w-full px-3 py-2 border rounded-lg mb-4">
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeRescheduleModal()" class="px-4 py-2 border rounded-lg">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-[color:var(--accent)] text-white rounded-lg">Submit Request</button>
      </div>
    </form>
  </div>
</div>

<footer class="bg-[color:var(--deep-2)] text-center py-6 border-t border-white/10 text-[color:var(--text)]">
  © <?php echo date('Y'); ?> PawVerse. All rights reserved.
</footer>

<script>
function openBookingModal(id,name){
  document.getElementById('vet_id').value=id;
  document.getElementById('vet_name').value=name;
  document.getElementById('bookingModal').classList.remove('hidden');
  document.body.style.overflow='hidden';
}
function closeBookingModal(){
  document.getElementById('bookingModal').classList.add('hidden');
  document.body.style.overflow='';
}
function openRescheduleModal(id){
  document.getElementById('reschedule_id').value=id;
  document.getElementById('rescheduleModal').classList.remove('hidden');
  document.body.style.overflow='hidden';
}
function closeRescheduleModal(){
  document.getElementById('rescheduleModal').classList.add('hidden');
  document.body.style.overflow='';
}
const switchEl=document.getElementById('themeSwitch'),body=document.body,saved=localStorage.getItem('pawverse_theme');
if(saved==='dark'){body.classList.add('theme-dark');switchEl.classList.add('on');}
switchEl.addEventListener('click',()=>{const d=body.classList.toggle('theme-dark');switchEl.classList.toggle('on');localStorage.setItem('pawverse_theme',d?'dark':'light');});
</script>
</body>
</html>
