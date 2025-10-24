<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

function add_activity_log($conn, $user_id, $action, $details = null) {
  $sql = "INSERT INTO activity_log (user_id, action, details) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  if ($stmt) {
    $stmt->bind_param("iss", $user_id, $action, $details);
    $stmt->execute();
    $stmt->close();
  }
}

$toast_message = '';
$toast_type = 'success';

// ADD PRODUCT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
  $name = trim($_POST['name']);
  $category = trim($_POST['category']);
  $price = floatval($_POST['price']);
  $stock = intval($_POST['stock']);
  $description = trim($_POST['description']);
  $image = '';

  if (!empty($_FILES['image']['name'])) {
    $uploadDir = "../assets/images/";
    $image = basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
  }

  $stmt = $conn->prepare("INSERT INTO products (name, category, price, stock, description, image) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssdss", $name, $category, $price, $stock, $description, $image);

  if ($stmt->execute()) {
    $toast_message = "✅ Product added successfully!";
    $toast_type = "success";
    add_activity_log($conn, $_SESSION['user_id'], 'Product added', "Product: {$name} ({$category})");
  } else {
    $toast_message = "❌ Failed to add product.";
    $toast_type = "error";
  }
  $stmt->close();
}

// DELETE PRODUCT
if (isset($_POST['confirm_delete'])) {
  $id = intval($_POST['delete_id']);
  $result = $conn->query("SELECT name, category FROM products WHERE id = $id");
  $deleted = $result ? $result->fetch_assoc() : null;

  if ($conn->query("DELETE FROM products WHERE id = $id")) {
    $toast_message = "🗑️ Product deleted successfully!";
    $toast_type = "success";
    if ($deleted) {
      add_activity_log($conn, $_SESSION['user_id'], 'Product deleted', "Deleted: {$deleted['name']} ({$deleted['category']})");
    }
  } else {
    $toast_message = "❌ Failed to delete product.";
    $toast_type = "error";
  }
}

$products = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>PawVerse Admin — Manage Products</title>
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
body {font-family:"Inter",sans-serif;background:var(--bg);color:var(--text);transition:background .3s,color .3s;}
.panel {background:var(--panel);border:1px solid var(--glass-border);box-shadow:var(--card-shadow);border-radius:.75rem;}
.muted {color:var(--muted);}
.btn {padding:.5rem 1rem;border-radius:.5rem;font-weight:500;cursor:pointer;}
.btn-primary {background:var(--accent);color:white;}
.btn-danger {background:#ef4444;color:white;}
.switch {width:52px;height:30px;border-radius:999px;padding:3px;display:inline-flex;align-items:center;cursor:pointer;background:rgba(0,0,0,0.08);}
.switch.on {background:linear-gradient(90deg,var(--accent),#60a5fa);}
.switch .knob {width:24px;height:24px;border-radius:999px;background:white;transform:translateX(0);transition:transform .25s;}
.switch.on .knob {transform:translateX(22px);}
.modal-panel {background:var(--panel);border-radius:1rem;padding:1.5rem;width:100%;max-width:36rem;}
.toast {position:fixed;top:1.25rem;left:50%;transform:translateX(-50%) translateY(-20px);z-index:9999;
  padding:.9rem 1.5rem;border-radius:.75rem;font-weight:600;box-shadow:0 6px 18px rgba(0,0,0,0.15);
  opacity:0;pointer-events:none;transition:all .4s ease;min-width:280px;text-align:center;}
.toast.show {opacity:1;transform:translateX(-50%) translateY(0);pointer-events:auto;}
.toast-success {background:#22c55e;color:white;}
.toast-warning {background:#eab308;color:#0f172a;}
.toast-error {background:#ef4444;color:white;}
body.theme-dark .toast-warning {color:#0f172a;}
</style>
</head>
<body>

<!-- TOAST -->
<?php if (!empty($toast_message)): ?>
<div id="toast" class="toast 
<?php 
echo match($toast_type) {
  'success' => 'toast-success',
  'warning' => 'toast-warning',
  default => 'toast-error'
};
?>">
<?php echo htmlspecialchars($toast_message); ?>
</div>
<script>
setTimeout(()=>document.getElementById('toast').classList.add('show'),100);
setTimeout(()=>document.getElementById('toast').classList.remove('show'),3300);
</script>
<?php endif; ?>

<div class="min-h-screen flex">
  <!-- SIDEBAR -->
  <aside class="fixed md:static inset-y-0 left-0 w-64 p-6 panel">
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
          <a href="products.php" class="block px-4 py-2 rounded-lg bg-[color:var(--accent)] text-white font-semibold">Products</a>
          <a href="orders.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Orders</a>
          <a href="users.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Users</a>
          <a href="vets.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Veterinarians</a>
          <a href="messages.php" class="block px-4 py-2 rounded-lg hover:bg-[color:var(--accent)]/10 transition">Messages</a>
        </nav>
      </div>
      <form method="POST" action="../auth/logout.php" class="pt-6">
        <button class="w-full py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition">Logout</button>
      </form>
    </div>
  </aside>

  <!-- MAIN (Full Width) -->
  <main class="flex-1 md:ml-64 p-8">
    <div class="flex flex-wrap items-center justify-between mb-6 w-full">
      <h1 class="text-2xl font-bold">Manage Products</h1>
      <div class="flex items-center gap-4 flex-shrink-0">
        <div class="flex items-center gap-3">
          <div class="text-sm muted">☀️</div>
          <div id="themeSwitch" class="switch"><div class="knob"></div></div>
          <div class="text-sm muted">🌙</div>
        </div>
        <button onclick="toggleModal(true)" class="btn btn-primary whitespace-nowrap">+ Add Product</button>
      </div>
    </div>

    <div class="panel p-4 overflow-x-auto w-full">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-200 dark:border-white/10 text-left">
            <th>ID</th><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Created</th><th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($products->num_rows > 0): while ($p = $products->fetch_assoc()): ?>
          <tr class="border-b border-slate-100 dark:border-white/10">
            <td class="py-3"><?php echo $p['id']; ?></td>
            <td><?php if ($p['image']): ?><img src="../assets/images/<?php echo htmlspecialchars($p['image']); ?>" class="w-12 h-12 object-cover rounded"><?php else: ?><div class="w-12 h-12 bg-slate-300 rounded"></div><?php endif; ?></td>
            <td><?php echo htmlspecialchars($p['name']); ?></td>
            <td><?php echo htmlspecialchars($p['category']); ?></td>
            <td>$<?php echo number_format($p['price'], 2); ?></td>
            <td><?php echo $p['stock']; ?></td>
            <td><?php echo date('M d, Y', strtotime($p['created_at'])); ?></td>
            <td class="text-right">
              <button onclick="confirmDelete(<?php echo $p['id']; ?>, '<?php echo htmlspecialchars($p['name']); ?>')" class="btn btn-danger text-xs">Delete</button>
            </td>
          </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="8" class="py-4 text-center muted">No products found.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

    <footer class="mt-8 text-sm muted text-center border-t border-slate-200 dark:border-white/10 pt-6">
      © <?php echo date('Y'); ?> PawVerse Admin Panel
    </footer>
  </main>
</div>

<!-- ADD MODAL -->
<div id="addModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="modal-panel">
    <h2 class="text-xl font-semibold mb-4">Add New Product</h2>
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="add_product" value="1">
      <div class="mb-3"><label>Name</label><input name="name" required class="w-full p-2 border rounded"></div>
      <div class="mb-3"><label>Category</label>
        <select name="category" required class="w-full p-2 border rounded">
          <option value="">-- Select Category --</option>
          <option value="Dog">Dog</option>
          <option value="Cat">Cat</option>
          <option value="Bird">Bird</option>
          <option value="Fish">Fish</option>
        </select>
      </div>
      <div class="mb-3"><label>Price</label><input type="number" step="0.01" name="price" required class="w-full p-2 border rounded"></div>
      <div class="mb-3"><label>Stock</label><input type="number" name="stock" required class="w-full p-2 border rounded"></div>
      <div class="mb-3"><label>Description</label><textarea name="description" rows="3" class="w-full p-2 border rounded"></textarea></div>
      <div class="mb-3"><label>Image</label><input type="file" name="image" accept="image/*" class="w-full"></div>
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
      <h2 class="text-lg font-semibold mb-3">Delete Product</h2>
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
