<?php
$password = 'bizbuddy2026';
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: prospects-view.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pw'])) {
    if ($_POST['pw'] === $password) {
        $_SESSION['auth'] = true;
    } else {
        $error = true;
    }
}

if (empty($_SESSION['auth'])) { ?>
<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Prospects — BizBuddy</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body style="background:#f8fafc;min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:system-ui,sans-serif">
  <form method="POST" style="background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,.08);padding:32px;width:100%;max-width:320px">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px">
      <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><rect x="0" y="12" width="3" height="8" fill="#15B956"/><rect x="5" y="6" width="3" height="14" fill="#15B956"/><rect x="10" y="9" width="3" height="11" fill="#15B956"/><rect x="15" y="0" width="3" height="20" fill="#15B956"/></svg>
      <span style="font-weight:700;color:#14392F">BizBuddy Prospects</span>
    </div>
    <?php if (!empty($error)): ?><p style="color:#dc2626;font-size:13px;margin-bottom:12px">Password salah.</p><?php endif; ?>
    <input type="password" name="pw" placeholder="Password" required
           style="width:100%;border:1.5px solid #e2e8f0;border-radius:10px;padding:11px 14px;font-size:14px;outline:none;box-sizing:border-box;margin-bottom:12px">
    <button type="submit" style="width:100%;background:#15B956;color:#fff;font-weight:700;padding:12px;border-radius:10px;border:none;font-size:14px;cursor:pointer">Log In</button>
  </form>
</body>
</html>
<?php exit; } ?>
<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Prospects — BizBuddy</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: system-ui, -apple-system, sans-serif; background: #f8fafc; min-height: 100vh; color: #1e293b; }

    .page { max-width: 900px; margin: 0 auto; padding: 24px 16px 48px; }

    .page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 24px; }
    .page-title { font-size: 20px; font-weight: 700; color: #0f172a; line-height: 1.3; }
    .page-sub { font-size: 13px; color: #94a3b8; margin-top: 3px; }
    .logout-link { font-size: 13px; color: #94a3b8; text-decoration: none; white-space: nowrap; padding-top: 4px; }
    .logout-link:hover { color: #64748b; }

    /* Status pill */
    .status-select { appearance: none; border: none; font-size: 12px; font-weight: 600; padding: 6px 26px 6px 10px; border-radius: 20px; cursor: pointer; outline: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='currentColor' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 8px center; transition: opacity .15s; width: 100%; }
    .status-select:hover { opacity: .85; }
    .s-baru      { background: #f1f5f9; color: #64748b; }
    .s-contacted { background: #fef9c3; color: #854d0e; }
    .s-proceed   { background: #dcfce7; color: #166534; }
    .s-taknak    { background: #fee2e2; color: #991b1b; }
    .saving      { opacity: .45; pointer-events: none; }

    /* Card layout (mobile default) */
    .card-list { display: flex; flex-direction: column; gap: 12px; }
    .card { background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,.07); padding: 16px; }
    .card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; margin-bottom: 12px; }
    .card-name { font-weight: 700; font-size: 15px; color: #0f172a; }
    .card-date { font-size: 11px; color: #94a3b8; margin-top: 2px; }
    .card-meta { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }
    .badge { font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 20px; background: #eff6ff; color: #1d4ed8; }
    .phone-link { font-size: 13px; font-weight: 600; color: #16a34a; text-decoration: none; font-family: monospace; }
    .phone-link:hover { text-decoration: underline; }
    .card-bisnes { font-size: 13px; color: #475569; }

    /* Table layout (desktop) */
    .table-wrap { display: none; background: #fff; border-radius: 16px; box-shadow: 0 1px 4px rgba(0,0,0,.07); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    thead th { text-align: left; padding: 12px 16px; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .06em; background: #f8fafc; border-bottom: 1px solid #f1f5f9; }
    tbody tr { border-bottom: 1px solid #f8fafc; transition: background .1s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #f8fafc; }
    tbody td { padding: 14px 16px; color: #334155; vertical-align: middle; }

    .count { font-size: 12px; color: #94a3b8; text-align: right; margin-top: 12px; }
    .empty { text-align: center; padding: 60px 16px; color: #94a3b8; font-size: 14px; }

    @media (min-width: 640px) {
      .page { padding: 32px 24px 64px; }
      .page-title { font-size: 22px; }
      .card-list { display: none; }
      .table-wrap { display: block; }
    }
  </style>
</head>
<body>
<div class="page">

  <div class="page-header">
    <div>
      <div class="page-title">Prospects — Berminat, Belum Bayar</div>
      <div class="page-sub">Dari landing page bizbuddyhq.com/business-audit</div>
    </div>
    <a href="?logout=1" class="logout-link">Log out</a>
  </div>

  <?php
  $file = __DIR__ . '/prospects.csv';
  $rows = [];
  if (file_exists($file) && ($fh = fopen($file, 'r')) !== false) {
      $headers = fgetcsv($fh);
      while (($row = fgetcsv($fh)) !== false) {
          $rows[] = array_combine($headers, $row);
      }
      fclose($fh);
  }
  foreach ($rows as $i => &$r) { $r['_idx'] = $i; }
  unset($r);
  $rows = array_reverse($rows);
  ?>

  <?php if (empty($rows)): ?>
    <div class="empty">Belum ada prospect lagi.</div>
  <?php else: ?>

    <?php
    function statusClass($s) {
      return match($s) { 'Dah Contact'=>'s-contacted','Nak Proceed'=>'s-proceed','Tak Nak'=>'s-taknak',default=>'s-baru' };
    }
    function statusSelect($idx, $current) {
      $opts = ['Baru'=>'🔵 Baru','Dah Contact'=>'🟡 Dah Contact','Nak Proceed'=>'🟢 Nak Proceed','Tak Nak'=>'🔴 Tak Nak'];
      $cls = statusClass($current);
      $html = "<select class='status-select {$cls}' data-idx='{$idx}' onchange='updateStatus(this)'>";
      foreach ($opts as $val => $label) {
        $sel = $val === $current ? ' selected' : '';
        $html .= "<option value='" . htmlspecialchars($val) . "'{$sel}>" . htmlspecialchars($label) . "</option>";
      }
      return $html . "</select>";
    }
    ?>

    <!-- Mobile cards -->
    <div class="card-list">
      <?php foreach ($rows as $r):
        $status = $r['Status'] ?? 'Baru';
        $phone = preg_replace('/\D/', '', $r['Phone']);
        $waPhone = preg_match('/^60/', $phone) ? $phone : '6' . ltrim($phone, '0');
      ?>
      <div class="card">
        <div class="card-top">
          <div>
            <div class="card-name"><?= htmlspecialchars($r['Nama']) ?></div>
            <div class="card-date"><?= htmlspecialchars($r['Tarikh']) ?></div>
          </div>
        </div>
        <div class="card-meta">
          <span class="badge"><?= htmlspecialchars($r['Jenis']) ?></span>
          <a href="https://wa.me/<?= $waPhone ?>" target="_blank" class="phone-link"><?= htmlspecialchars($r['Phone']) ?></a>
        </div>
        <div class="card-bisnes" style="margin-bottom:12px"><?= htmlspecialchars($r['Bisnes']) ?></div>
        <?= statusSelect((int)$r['_idx'], $status) ?>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Desktop table -->
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Tarikh</th>
            <th>Nama</th>
            <th>Phone</th>
            <th>Bisnes</th>
            <th>Jenis</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r):
            $status = $r['Status'] ?? 'Baru';
            $phone = preg_replace('/\D/', '', $r['Phone']);
            $waPhone = preg_match('/^60/', $phone) ? $phone : '6' . ltrim($phone, '0');
          ?>
          <tr>
            <td style="color:#94a3b8;font-size:12px;white-space:nowrap"><?= htmlspecialchars($r['Tarikh']) ?></td>
            <td style="font-weight:600"><?= htmlspecialchars($r['Nama']) ?></td>
            <td><a href="https://wa.me/<?= $waPhone ?>" target="_blank" class="phone-link"><?= htmlspecialchars($r['Phone']) ?></a></td>
            <td><?= htmlspecialchars($r['Bisnes']) ?></td>
            <td><span class="badge"><?= htmlspecialchars($r['Jenis']) ?></span></td>
            <td style="min-width:155px"><?= statusSelect((int)$r['_idx'], $status) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="count"><?= count($rows) ?> prospect</div>

  <?php endif; ?>
</div>

<script>
  const clsMap = { 'Baru':'s-baru', 'Dah Contact':'s-contacted', 'Nak Proceed':'s-proceed', 'Tak Nak':'s-taknak' };
  async function updateStatus(sel) {
    sel.className = 'status-select saving ' + (clsMap[sel.value] || 's-baru');
    const fd = new FormData();
    fd.append('idx', sel.dataset.idx);
    fd.append('status', sel.value);
    await fetch('update-prospect-status.php', { method: 'POST', body: fd });
    sel.className = 'status-select ' + (clsMap[sel.value] || 's-baru');
  }
</script>
</body>
</html>
