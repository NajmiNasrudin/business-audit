<?php
/* =====================================================
   BizBuddy Coaching — checkout/coaching.php
   Clean, dedicated coaching checkout.
   GET  ?p=coaching-zoom|coaching-onsite  -> show form
   POST                                    -> create CHIP purchase, redirect
   ?paid=1                                 -> thank-you
   Reuses /checkout/config.php (CHIP keys + $PRODUCTS) — no secret duplicated.
   ===================================================== */
require_once __DIR__ . '/config.php';

$WA = '60122541050';

/* which package */
$p = isset($_REQUEST['p']) ? preg_replace('/[^a-z\-]/', '', $_REQUEST['p']) : '';
if (!isset($PRODUCTS[$p]) || (($PRODUCTS[$p]['group'] ?? '') !== 'coaching')) {
    $p = 'coaching-zoom';
}
$pkg   = $PRODUCTS[$p];
$price = 'RM ' . number_format((float)$pkg['price'], 0);
$paid  = isset($_GET['paid']);
$err   = isset($_GET['error']) ? preg_replace('/[^a-z]/', '', $_GET['error']) : '';

/* per-package display bits */
$META = [
    'coaching-zoom'   => ['tag' => 'Online · Remote',        'dur' => '2 jam · Google Meet atau Zoom'],
    'coaching-onsite' => ['tag' => 'Face-to-face · Setia Alam', 'dur' => '3–4 jam · Setia Alam, Selangor'],
];
$tag = $META[$p]['tag'] ?? '';
$dur = $META[$p]['dur'] ?? '';

/* ---- handle payment ---- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = preg_replace('/[^0-9+]/', '', $_POST['phone'] ?? '');
    $keep  = http_build_query(['p' => $p, 'name' => $name, 'email' => $email, 'phone' => $phone]);

    if ($name === '' || $phone === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: coaching.php?error=invalid&' . $keep);
        exit;
    }

    $ref = 'BBC-' . strtoupper(str_replace('coaching-', '', $p)) . '-' . date('Ymd')
         . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));

    $payload = [
        'brand_id'  => CHIP_BRAND_ID,
        'reference' => $ref,
        'client'    => ['email' => $email, 'full_name' => mb_substr($name, 0, 80), 'phone' => $phone],
        'purchase'  => ['currency' => 'MYR', 'products' => [[
            'name'     => $pkg['name'] . ' — BizBuddy 1-on-1 Coaching',
            'price'    => (int) round((float)$pkg['price'] * 100),
            'quantity' => 1,
        ]]],
        'success_redirect' => SITE_URL . '/coaching.php?paid=1&p=' . $p,
        'failure_redirect' => SITE_URL . '/coaching.php?error=chip&' . $keep,
        'send_receipt'     => true,
    ];

    $ch = curl_init(rtrim(CHIP_BASE_URL, '/') . '/purchases/');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . CHIP_SECRET_KEY, 'Content-Type: application/json'],
    ]);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $result = json_decode($resp, true);

    if ($code >= 300 || !isset($result['checkout_url'])) {
        error_log('[Coaching] CHIP error http=' . $code . ' resp=' . substr((string)$resp, 0, 400));
        header('Location: coaching.php?error=chip&' . $keep);
        exit;
    }

    header('Location: ' . $result['checkout_url']);
    exit;
}
?><!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex">
<title><?= $paid ? 'Terima kasih' : 'Checkout — ' . htmlspecialchars($pkg['name']) ?> · BizBuddy Coaching</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{--brand:#15B956;--brand-dark:#128A45;--brand-soft:#DCFCE7;--ink:#0B2E1C;--text:#0F1419;--muted:#4B5563;--dim:#6B7280;--border:#E5E7EB;--bg:#F3F6F4;--radius:16px;--pill:999px;--red:#DC2626;--red-soft:#FEE2E2;}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,sans-serif;color:var(--text);background:var(--bg);line-height:1.6;padding:28px 18px 56px;}
.wrap{max-width:940px;margin:0 auto;}
.top{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;}
.logo{font-size:20px;font-weight:800;letter-spacing:-.02em;color:var(--text);text-decoration:none;}
.logo span{color:var(--brand);}
.back{color:var(--muted);font-size:14px;text-decoration:none;font-weight:600;}
.back:hover{color:var(--text);}
.grid{display:grid;grid-template-columns:1.25fr 1fr;gap:22px;align-items:start;}
.card{background:#fff;border:1px solid var(--border);border-radius:var(--radius);padding:30px;}
h1{font-size:24px;font-weight:800;letter-spacing:-.02em;line-height:1.2;margin-bottom:4px;}
.lead{color:var(--muted);font-size:15px;margin-bottom:22px;}
label{display:block;font-size:13.5px;font-weight:700;margin:16px 0 6px;}
input{width:100%;font-family:inherit;font-size:16px;padding:13px 15px;border:1.5px solid var(--border);border-radius:11px;background:#fff;color:var(--text);transition:border-color .15s;}
input::placeholder{color:#9CA3AF;}
input:focus{outline:none;border-color:var(--brand);box-shadow:0 0 0 3px rgba(21,185,86,.12);}
.btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;text-align:center;margin-top:24px;padding:16px;font-family:inherit;font-size:16px;font-weight:700;color:#fff;background:var(--brand);border:none;border-radius:var(--pill);cursor:pointer;box-shadow:0 6px 18px rgba(21,185,86,.28);text-decoration:none;transition:background .15s,transform .15s;}
.btn:hover{background:var(--brand-dark);transform:translateY(-1px);}
.err{background:var(--red-soft);color:var(--red);font-size:14px;padding:12px 14px;border-radius:11px;margin-bottom:18px;}
.pays{display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-top:16px;font-size:12px;color:var(--dim);}
.pays b{font-weight:600;color:var(--muted);}
.chip{border:1px solid var(--border);border-radius:7px;padding:3px 9px;font-size:11.5px;font-weight:600;color:var(--muted);}
.ssl{text-align:center;font-size:12px;color:var(--dim);margin-top:16px;}
/* summary (dark) */
.sum{background:var(--ink);color:#fff;border:none;position:sticky;top:20px;}
.sum-eyebrow{font-size:11.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#8FE9B4;margin-bottom:14px;}
.sum-tag{display:inline-block;background:rgba(255,255,255,.12);color:#C9F5D9;font-size:11.5px;font-weight:600;padding:4px 10px;border-radius:var(--pill);margin-bottom:12px;}
.sum-name{font-size:20px;font-weight:800;letter-spacing:-.02em;line-height:1.25;margin-bottom:4px;}
.sum-dur{font-size:13.5px;color:rgba(255,255,255,.62);}
.sum-line{height:1px;background:rgba(255,255,255,.14);margin:20px 0;}
.sum-row{display:flex;justify-content:space-between;align-items:center;font-size:14px;margin-bottom:12px;color:rgba(255,255,255,.82);}
.sum-row .v{color:#fff;font-weight:600;}
.sum-total{display:flex;justify-content:space-between;align-items:baseline;margin-top:6px;}
.sum-total .l{font-size:16px;font-weight:700;}
.sum-total .r{font-size:26px;font-weight:800;letter-spacing:-.02em;}
.sum-note{background:rgba(21,185,86,.14);border:1px solid rgba(21,185,86,.3);border-radius:12px;padding:13px 15px;margin-top:20px;font-size:13px;color:#C9F5D9;line-height:1.5;}
.sum-note b{color:#fff;font-weight:700;}
/* thank you */
.thanks{max-width:520px;margin:0 auto;text-align:center;}
.tick{width:66px;height:66px;border-radius:50%;background:var(--brand-soft);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;}
.tick svg{width:32px;height:32px;}
.thanks h1{font-size:27px;margin-bottom:8px;}
.thanks p{color:var(--muted);font-size:15.5px;margin-bottom:24px;}
@media (max-width:760px){
  body{padding:20px 14px 44px;}
  .grid{grid-template-columns:1fr;gap:16px;}
  .card{padding:22px 20px;}
  .sum{position:static;order:-1;}
  h1{font-size:21px;}
}
</style>
</head>
<body>
<div class="wrap">
  <div class="top">
    <a href="/coaching/" class="logo">Biz<span>Buddy</span></a>
    <a href="/coaching/#book" class="back">&larr; Balik ke pakej</a>
  </div>

<?php if ($paid): ?>
  <div class="card thanks">
    <div class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="#128A45" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
    <h1>Bayaran diterima. Terima kasih!</h1>
    <p>Resit dah dihantar ke email anda. Najmi akan WhatsApp anda tak lama lagi untuk aturkan slot <?= htmlspecialchars($pkg['name']) ?>.</p>
    <a class="btn" href="https://wa.me/<?= $WA ?>?text=<?= rawurlencode('Hi Najmi, saya baru bayar ' . $pkg['name'] . '. Bila slot available?') ?>" target="_blank" rel="noopener">WhatsApp Najmi sekarang</a>
  </div>
<?php else: ?>
  <div class="grid">
    <div class="card">
      <h1>Maklumat Pembeli</h1>
      <div class="lead">Isi 3 maklumat ni, pastu terus ke pembayaran CHIP.</div>

      <?php if ($err === 'invalid'): ?>
        <div class="err">Ada maklumat tak lengkap atau tak sah. Sila semak semula nama, email &amp; nombor.</div>
      <?php elseif ($err === 'chip'): ?>
        <div class="err">Ada masalah dengan payment gateway. Cuba lagi, atau WhatsApp saya.</div>
      <?php endif; ?>

      <form action="coaching.php" method="post" autocomplete="on">
        <input type="hidden" name="p" value="<?= htmlspecialchars($p) ?>">
        <label for="name">Nama penuh</label>
        <input type="text" id="name" name="name" required maxlength="80" autocomplete="name" placeholder="Contoh: Ahmad bin Ali" value="<?= htmlspecialchars($_GET['name'] ?? '') ?>">
        <label for="email">Alamat email</label>
        <input type="email" id="email" name="email" required maxlength="120" autocomplete="email" placeholder="email@anda.com" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
        <label for="phone">Nombor WhatsApp</label>
        <input type="tel" id="phone" name="phone" required maxlength="20" autocomplete="tel" placeholder="01X-XXXXXXX" value="<?= htmlspecialchars($_GET['phone'] ?? '') ?>">
        <button type="submit" class="btn">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          Bayar <?= $price ?> ke CHIP
        </button>
        <div class="pays"><b>Diterima:</b><span class="chip">FPX</span><span class="chip">Visa</span><span class="chip">Mastercard</span><span class="chip">DuitNow</span></div>
        <div class="ssl">🔒 256-bit SSL · Diproses selamat oleh CHIP · Resit ke email anda</div>
      </form>
    </div>

    <div class="card sum">
      <div class="sum-eyebrow">Ringkasan Pesanan</div>
      <?php if ($tag): ?><span class="sum-tag"><?= htmlspecialchars($tag) ?></span><?php endif; ?>
      <div class="sum-name"><?= htmlspecialchars($pkg['name']) ?></div>
      <div class="sum-dur"><?= htmlspecialchars($dur) ?></div>
      <div class="sum-line"></div>
      <div class="sum-row"><span>Coaching 1-on-1 dengan Najmi</span><span class="v"><?= $price ?></span></div>
      <div class="sum-row"><span>Follow-up notes</span><span class="v">✓</span></div>
      <div class="sum-line"></div>
      <div class="sum-total"><span class="l">Jumlah</span><span class="r"><?= $price ?></span></div>
      <div class="sum-note"><b>Lepas bayar:</b> Najmi WhatsApp anda untuk aturkan slot. Bayaran penuh, sekali sahaja.</div>
    </div>
  </div>
<?php endif; ?>
</div>
</body>
</html>
