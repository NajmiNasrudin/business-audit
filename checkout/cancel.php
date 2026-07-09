<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembayaran Tidak Berjaya — BizBuddy Audit</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: { extend: { colors: { forest: '#14392F', cream: '#F4EDD8', sage: '#15B956', sand: '#E8DFC6', slate: '#4A4A4A', bone: '#FAFAF7' }, fontFamily: { display: ['Fraunces', 'Georgia', 'serif'], body: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'] } } }
    }
  </script>
</head>
<body class="bg-bone min-h-screen font-body">

  <header class="bg-forest py-4 px-4">
    <div class="max-w-2xl mx-auto flex items-center gap-2">
      <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
        <rect x="0"  y="12" width="3" height="8"  fill="#15B956"/>
        <rect x="5"  y="6"  width="3" height="14" fill="#15B956"/>
        <rect x="10" y="9"  width="3" height="11" fill="#15B956"/>
        <rect x="15" y="0"  width="3" height="20" fill="#15B956"/>
        <rect x="19" y="13" width="1" height="7"  fill="#15B956"/>
      </svg>
      <span class="text-cream font-semibold text-sm">BizBuddy</span>
    </div>
  </header>

  <main class="max-w-lg mx-auto px-4 py-16 text-center">

    <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
      <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
        <circle cx="18" cy="18" r="18" fill="#ef4444" opacity="0.12"/>
        <path d="M13 13L23 23M23 13L13 23" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round"/>
      </svg>
    </div>

    <h1 class="font-display text-forest text-3xl font-semibold mb-3">Pembayaran Tidak Berjaya</h1>
    <p class="text-slate text-base leading-relaxed mb-8">
      Transaksi dibatalkan atau tidak berjaya. Tiada bayaran telah ditolak. Cuba semula atau hubungi saya terus.
    </p>

    <div class="space-y-3">
      <a href="index.php"
         class="block w-full bg-sage text-white font-semibold py-3.5 px-6 rounded-lg text-sm hover:bg-green-600 transition-colors">
        Cuba Semula
      </a>
      <a href="https://wa.me/60122541050?text=Hi%2C%20saya%20ada%20masalah%20nak%20buat%20payment%20untuk%20Audit%20Lite."
         target="_blank" rel="noopener"
         class="block w-full bg-white border border-sand text-forest font-semibold py-3.5 px-6 rounded-lg text-sm hover:bg-sand/30 transition-colors">
        Hubungi via WhatsApp
      </a>
    </div>

  </main>

</body>
</html>
