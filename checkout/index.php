<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout — BizBuddy Audit</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Meta Pixel Code -->
  <script>
  !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
  n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
  document,'script','https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '1346410124247236');
  fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1346410124247236&ev=PageView&noscript=1"/></noscript>
  <!-- End Meta Pixel Code -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            forest: '#14392F',
            'forest-soft': '#1F4D40',
            cream: '#F4EDD8',
            sage: '#15B956',
            'sage-dark': '#0F8C42',
            sand: '#E8DFC6',
            coal: '#1A1A1A',
            slate: '#4A4A4A',
            bone: '#FAFAF7',
          },
          fontFamily: {
            display: ['Fraunces', 'Georgia', 'serif'],
            body: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <style>
    body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
    .product-card { cursor: pointer; transition: all 0.2s; }
    .product-card:has(input:checked) { border-color: #15B956; background: #f0fdf4; }
    .product-card input[type="radio"] { accent-color: #15B956; }
    .btn-primary { background: #15B956; color: #fff; font-weight: 600; padding: 0.875rem 2rem; border-radius: 0.5rem; display: inline-block; width: 100%; text-align: center; font-size: 1rem; transition: background 0.2s; border: none; cursor: pointer; }
    .btn-primary:hover { background: #0F8C42; }
    .btn-primary:disabled { background: #9ca3af; cursor: not-allowed; }
    input:focus, select:focus, textarea:focus { outline: 2px solid #15B956; outline-offset: 2px; }
  </style>
</head>
<body class="bg-bone min-h-screen">

  <!-- Header -->
  <header class="bg-forest py-4 px-4">
    <div class="max-w-2xl mx-auto flex items-center gap-2">
      <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="0"  y="12" width="3" height="8"  fill="#15B956"/>
        <rect x="5"  y="6"  width="3" height="14" fill="#15B956"/>
        <rect x="10" y="9"  width="3" height="11" fill="#15B956"/>
        <rect x="15" y="0"  width="3" height="20" fill="#15B956"/>
        <rect x="19" y="13" width="1" height="7"  fill="#15B956"/>
      </svg>
      <span class="text-cream font-semibold text-sm">BizBuddy</span>
      <span class="text-cream/40 text-sm">/</span>
      <span class="text-cream/70 text-sm">Checkout</span>
      <a href="https://bizbuddyhq.com/business-audit" class="ml-auto text-cream/60 hover:text-cream text-sm flex items-center gap-1 transition-colors">
        ← Kembali ke website
      </a>
    </div>
  </header>

  <main class="max-w-2xl mx-auto px-4 py-10">

    <!-- Title -->
    <div class="mb-8">
      <h1 class="font-display text-forest text-3xl font-semibold mb-1">Pilih Pakej</h1>
      <p class="text-slate text-sm">Pembayaran selamat melalui Chip — FPX, kad kredit/debit, e-wallet.</p>
    </div>

    <form action="pay.php" method="POST" id="checkout-form">

      <!-- Product Selection -->
      <div class="space-y-3 mb-8">
        <?php foreach ($PRODUCTS as $product): ?>
          <?php if ($product['available']): ?>
            <label class="product-card block bg-white border-2 border-sand rounded-lg p-5">
              <div class="flex items-start gap-4">
                <?php $preselect = $_GET['product'] ?? 'audit-lite'; ?>
                <input type="radio" name="product_id" value="<?= htmlspecialchars($product['id']) ?>"
                       class="mt-1 w-4 h-4 shrink-0" <?= $product['id'] === $preselect ? 'checked' : '' ?> required>
                <div class="flex-1">
                  <div class="flex items-center justify-between gap-2">
                    <h3 class="font-semibold text-coal text-base"><?= htmlspecialchars($product['name']) ?></h3>
                    <span class="font-display text-forest text-xl font-semibold shrink-0">RM<?= number_format($product['price']) ?></span>
                  </div>
                  <p class="text-slate text-sm mt-1"><?= htmlspecialchars($product['description']) ?></p>
                  <span class="slot-badge" data-product="<?= htmlspecialchars($product['id']) ?>" style="display:none;font-size:12px;font-weight:600;margin-top:5px;display:inline-block"></span>
                </div>
              </div>
            </label>
          <?php else: ?>
            <div class="block bg-white border border-sand rounded-lg p-5 opacity-50">
              <div class="flex items-center justify-between">
                <div>
                  <span class="inline-block bg-forest text-sage font-mono text-xs tracking-widest uppercase px-3 py-0.5 rounded-full mb-2">Coming Soon</span>
                  <h3 class="font-semibold text-coal text-base"><?= htmlspecialchars($product['name']) ?></h3>
                </div>
              </div>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>

      <!-- Divider -->
      <div class="border-t border-sand mb-8"></div>

      <!-- Customer Form -->
      <h2 class="font-semibold text-coal text-base mb-5">Maklumat Anda</h2>
      <div class="space-y-4 mb-8">

        <div>
          <label class="block text-sm font-medium text-coal mb-1.5">Nama Penuh <span class="text-red-500">*</span></label>
          <input type="text" name="name" required placeholder="Contoh: Ahmad bin Ali"
                 class="w-full border border-sand rounded-lg px-4 py-3 text-sm text-coal bg-white">
        </div>

        <div>
          <label class="block text-sm font-medium text-coal mb-1.5">E-mel <span class="text-red-500">*</span></label>
          <input type="email" name="email" required placeholder="nama@email.com"
                 class="w-full border border-sand rounded-lg px-4 py-3 text-sm text-coal bg-white">
        </div>

        <div>
          <label class="block text-sm font-medium text-coal mb-1.5">No. Telefon <span class="text-red-500">*</span></label>
          <input type="tel" name="phone" required placeholder="0123456789"
                 class="w-full border border-sand rounded-lg px-4 py-3 text-sm text-coal bg-white">
        </div>

        <div>
          <label class="block text-sm font-medium text-coal mb-1.5">Nama Bisnes <span class="text-red-500">*</span></label>
          <input type="text" name="business_name" required placeholder="Nama kedai / syarikat anda"
                 class="w-full border border-sand rounded-lg px-4 py-3 text-sm text-coal bg-white">
        </div>

        <div>
          <label class="block text-sm font-medium text-coal mb-1.5">Jenis Bisnes</label>
          <input type="text" name="business_type" placeholder="Contoh: F&B, Retail, Servis, E-commerce..."
                 class="w-full border border-sand rounded-lg px-4 py-3 text-sm text-coal bg-white">
        </div>

      </div>

      <!-- Order Summary -->
      <div class="bg-forest/5 border border-forest/10 rounded-lg p-5 mb-6" id="order-summary">
        <div class="flex items-center justify-between mb-3">
          <span class="text-sm font-medium text-coal">Pakej dipilih</span>
          <span class="text-sm font-semibold text-coal" id="summary-product"><?= htmlspecialchars($PRODUCTS[$preselect]['name'] ?? 'Audit Lite') ?></span>
        </div>
        <div class="flex items-center justify-between border-t border-forest/10 pt-3">
          <span class="font-semibold text-coal">Jumlah</span>
          <span class="font-display text-forest text-xl font-semibold" id="summary-price">RM<?= number_format($PRODUCTS[$preselect]['price'] ?? 497) ?></span>
        </div>
      </div>

      <!-- Security note -->
      <p class="text-slate/60 text-xs text-center mb-5">🔒 Pembayaran selamat melalui Chip. Data anda tidak disimpan di server kami.</p>

      <!-- Consent checkbox -->
      <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;padding:14px 16px;margin-bottom:20px;display:flex;align-items:flex-start;gap:12px">
        <input type="checkbox" id="consent-check" name="consent" value="1"
               style="margin-top:3px;width:16px;height:16px;accent-color:#15B956;flex-shrink:0;cursor:pointer">
        <label for="consent-check" style="font-size:13px;color:#1A1A1A;line-height:1.5;cursor:pointer">
          Saya dah baca dan setuju dengan <a href="/business-audit/terma-polisi.html" target="_blank" style="color:#15B956;font-weight:600;text-decoration:underline">Terma &amp; Polisi</a> BizBuddy, termasuk refund policy dan scope of service.
        </label>
      </div>

      <!-- Submit -->
      <button type="submit" id="submit-btn" class="btn-primary" disabled style="opacity:0.5;cursor:not-allowed">
        Teruskan ke Pembayaran →
      </button>

    </form>

  </main>

  <!-- Footer -->
  <footer class="text-center py-8 text-slate/40 text-xs">
    &copy; <?= date('Y') ?> BizBuddy · <a href="https://bizbuddyhq.com/business-audit" class="hover:text-slate transition-colors">Kembali ke halaman audit</a>
  </footer>

  <script>
    const products = <?= json_encode(array_values(array_filter($PRODUCTS, fn($p) => $p['available']))) ?>;
    const radios = document.querySelectorAll('input[type="radio"][name="product_id"]');
    const summaryProduct = document.getElementById('summary-product');
    const summaryPrice = document.getElementById('summary-price');

    document.getElementById('checkout-form').addEventListener('submit', function() {
      const selected = products.find(p => p.id === document.querySelector('input[name="product_id"]:checked')?.value);
      if (selected && typeof fbq !== 'undefined') {
        fbq('track', 'InitiateCheckout', { value: selected.price, currency: 'MYR', content_name: selected.name });
      }
    });

    function syncSummary() {
      const checked = document.querySelector('input[name="product_id"]:checked');
      if (!checked) return;
      const selected = products.find(p => p.id === checked.value);
      if (selected) {
        summaryProduct.textContent = selected.name;
        summaryPrice.textContent = 'RM' + selected.price.toLocaleString();
      }
    }
    radios.forEach(radio => radio.addEventListener('change', syncSummary));
    syncSummary();

    // Consent gate
    const consentCheck = document.getElementById('consent-check');
    const submitBtn = document.getElementById('submit-btn');
    consentCheck.addEventListener('change', function() {
      submitBtn.disabled = !this.checked;
      submitBtn.style.opacity = this.checked ? '1' : '0.5';
      submitBtn.style.cursor = this.checked ? 'pointer' : 'not-allowed';
    });

    // Log consent on submit
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
      const checked = document.querySelector('input[name="product_id"]:checked');
      if (consentCheck.checked && checked) {
        const product = products.find(p => p.id === checked.value);
        fetch('save-consent.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({
            tier: checked.value,
            amount: product ? product.price : '',
            email: document.querySelector('input[name="email"]').value,
            policy_version: 'v1.0-2026-07-09'
          })
        }).catch(() => {});
      }
    });

    (async () => {
      try {
        const res = await fetch('slots.php');
        const data = await res.json();
        document.querySelectorAll('.slot-badge').forEach(el => {
          const s = data[el.dataset.product];
          if (!s || s.limit === 0) return;
          el.style.display = 'inline-block';
          if (s.remaining === 0) {
            el.style.color = '#dc2626';
            el.textContent = '🔴 Slot penuh bulan ini';
          } else if (s.remaining <= 2) {
            el.style.color = '#d97706';
            el.textContent = '⚠️ ' + s.remaining + ' slot je lagi!';
          } else {
            el.style.color = '#15B956';
            el.textContent = '✦ ' + s.remaining + ' slot tersedia';
          }
        });
      } catch {}
    })();
  </script>

</body>
</html>
