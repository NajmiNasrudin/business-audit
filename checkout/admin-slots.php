<?php
$password = 'bizbuddy2026';
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin-slots.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pw'])) {
    if ($_POST['pw'] === $password) {
        $_SESSION['slots_auth'] = true;
    } else {
        $login_error = true;
    }
}

// Save slots
$config_file = __DIR__ . '/slots-config.json';
$save_msg = '';
if (!empty($_SESSION['slots_auth']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['slots'])) {
    $current = file_exists($config_file) ? json_decode(file_get_contents($config_file), true) : [];
    foreach ($_POST['slots'] as $id => $val) {
        $current[$id] = max(0, (int)$val);
    }
    file_put_contents($config_file, json_encode($current, JSON_PRETTY_PRINT));
    $save_msg = 'Slot berjaya disimpan.';
}

if (empty($_SESSION['slots_auth'])) { ?>
<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Slots — BizBuddy</title>
</head>
<body style="background:#f8fafc;min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:system-ui,sans-serif">
  <form method="POST" style="background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,.08);padding:32px;width:100%;max-width:320px">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px">
      <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><rect x="0" y="12" width="3" height="8" fill="#15B956"/><rect x="5" y="6" width="3" height="14" fill="#15B956"/><rect x="10" y="9" width="3" height="11" fill="#15B956"/><rect x="15" y="0" width="3" height="20" fill="#15B956"/></svg>
      <span style="font-weight:700;color:#14392F">Admin Slots</span>
    </div>
    <?php if (!empty($login_error)): ?><p style="color:#dc2626;font-size:13px;margin-bottom:12px">Password salah.</p><?php endif; ?>
    <input type="password" name="pw" placeholder="Password" required
           style="width:100%;border:1.5px solid #e2e8f0;border-radius:10px;padding:11px 14px;font-size:14px;outline:none;box-sizing:border-box;margin-bottom:12px">
    <button type="submit" style="width:100%;background:#15B956;color:#fff;font-weight:700;padding:12px;border-radius:10px;border:none;font-size:14px;cursor:pointer">Log In</button>
  </form>
</body>
</html>
<?php exit; }

require_once __DIR__ . '/config.php';
$limits = file_exists($config_file) ? json_decode(file_get_contents($config_file), true) : [];

// Count used slots this month
$used = [];
$orders_file = __DIR__ . '/orders.csv';
if (file_exists($orders_file) && ($fh = fopen($orders_file, 'r')) !== false) {
    $headers = fgetcsv($fh);
    $month = date('Y-m');
    while (($row = fgetcsv($fh)) !== false) {
        $data = array_combine($headers, $row);
        if (
            isset($data['Date'], $data['Status'], $data['Product']) &&
            strtoupper($data['Status']) === 'PAID' &&
            str_starts_with($data['Date'], $month)
        ) {
            foreach ($PRODUCTS as $id => $p) {
                if ($p['name'] === $data['Product']) {
                    $used[$id] = ($used[$id] ?? 0) + 1;
                    break;
                }
            }
        }
    }
    fclose($fh);
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Slots — BizBuddy</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: system-ui, -apple-system, sans-serif; background: #f8fafc; min-height: 100vh; color: #1e293b; }
    .page { max-width: 640px; margin: 0 auto; padding: 32px 16px 64px; }
    .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; }
    .logo { display: flex; align-items: center; gap: 8px; font-weight: 700; color: #14392F; font-size: 16px; }
    .logout { font-size: 13px; color: #94a3b8; text-decoration: none; }
    .logout:hover { color: #64748b; }
    h1 { font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
    .sub { font-size: 13px; color: #94a3b8; margin-bottom: 28px; }
    .card { background: #fff; border-radius: 16px; box-shadow: 0 1px 4px rgba(0,0,0,.07); padding: 24px; margin-bottom: 16px; }
    .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
    .card-name { font-weight: 700; font-size: 16px; color: #0f172a; }
    .card-price { font-size: 14px; color: #15B956; font-weight: 600; }
    .progress-row { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
    .progress-bar { flex: 1; height: 8px; background: #f1f5f9; border-radius: 99px; overflow: hidden; }
    .progress-fill { height: 100%; border-radius: 99px; background: #15B956; transition: width .3s; }
    .progress-fill.warn { background: #f59e0b; }
    .progress-fill.full { background: #ef4444; }
    .progress-label { font-size: 13px; font-weight: 600; color: #475569; white-space: nowrap; }
    .field-row { display: flex; align-items: center; gap: 12px; }
    .field-label { font-size: 13px; color: #64748b; flex: 1; }
    .slot-input { width: 80px; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 8px 12px; font-size: 15px; font-weight: 600; text-align: center; color: #0f172a; outline: none; }
    .slot-input:focus { border-color: #15B956; }
    .save-btn { width: 100%; background: #15B956; color: #fff; font-weight: 700; padding: 14px; border-radius: 12px; border: none; font-size: 15px; cursor: pointer; margin-top: 8px; transition: background .2s; }
    .save-btn:hover { background: #0F8C42; }
    .success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 10px; font-size: 14px; margin-bottom: 20px; }
    .month-badge { font-size: 11px; font-weight: 600; color: #64748b; background: #f1f5f9; padding: 4px 10px; border-radius: 99px; }
  </style>
</head>
<body>
<div class="page">

  <div class="header">
    <div class="logo">
      <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><rect x="0" y="12" width="3" height="8" fill="#15B956"/><rect x="5" y="6" width="3" height="14" fill="#15B956"/><rect x="10" y="9" width="3" height="11" fill="#15B956"/><rect x="15" y="0" width="3" height="20" fill="#15B956"/></svg>
      BizBuddy
    </div>
    <a href="?logout=1" class="logout">Log out</a>
  </div>

  <h1>Manage Slots</h1>
  <p class="sub">Set berapa slot boleh dibuka untuk setiap pakej bulan ini.</p>

  <?php if ($save_msg): ?>
    <div class="success"><?= htmlspecialchars($save_msg) ?></div>
  <?php endif; ?>

  <form method="POST">
    <?php foreach ($PRODUCTS as $id => $product):
      $limit = $limits[$id] ?? 0;
      $booked = $used[$id] ?? 0;
      $remaining = max(0, $limit - $booked);
      $pct = $limit > 0 ? min(100, round($booked / $limit * 100)) : 0;
      $fill_class = $pct >= 100 ? 'full' : ($pct >= 75 ? 'warn' : '');
    ?>
    <div class="card">
      <div class="card-header">
        <span class="card-name"><?= htmlspecialchars($product['name']) ?></span>
        <span class="card-price">RM<?= number_format($product['price']) ?></span>
      </div>

      <div class="progress-row">
        <div class="progress-bar">
          <div class="progress-fill <?= $fill_class ?>" style="width:<?= $pct ?>%"></div>
        </div>
        <span class="progress-label"><?= $booked ?>/<?= $limit ?> slot</span>
      </div>

      <div class="field-row">
        <span class="field-label">
          Slot limit bulan <strong><?= date('F Y') ?></strong><br>
          <span style="color:#15B956;font-size:12px"><?= $remaining ?> slot berbaki</span>
        </span>
        <input type="number" name="slots[<?= $id ?>]" value="<?= $limit ?>" min="0" max="99" class="slot-input">
      </div>
    </div>
    <?php endforeach; ?>

    <button type="submit" name="slots[_save]" value="1" class="save-btn">Simpan Perubahan</button>
  </form>

</div>
</body>
</html>
