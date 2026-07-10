<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembayaran Berjaya — BizBuddy Audit</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: { extend: { colors: { forest: '#14392F', cream: '#F4EDD8', sage: '#15B956', sand: '#E8DFC6', slate: '#4A4A4A', bone: '#FAFAF7' }, fontFamily: { display: ['Fraunces', 'Georgia', 'serif'], body: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'] } } }
    }
  </script>

  <!-- Meta Pixel Code -->
  <script>
  !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
  n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
  document,'script','https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '1346410124247236');
  fbq('track', 'PageView');
  fbq('track', 'Purchase', { value: 497.00, currency: 'MYR', content_name: 'Audit Lite' });
  </script>
  <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1346410124247236&ev=Purchase&noscript=1"/></noscript>
  <!-- End Meta Pixel Code -->

</head>
<body class="bg-bone min-h-screen font-body">

  <!-- Header -->
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

    <!-- Success icon -->
    <div class="w-20 h-20 bg-sage/10 rounded-full flex items-center justify-center mx-auto mb-6">
      <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
        <circle cx="18" cy="18" r="18" fill="#15B956" opacity="0.15"/>
        <path d="M10 18L15.5 23.5L26 13" stroke="#15B956" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>

    <h1 class="font-display text-forest text-3xl font-semibold mb-3">Pembayaran Berjaya!</h1>
    <p class="text-slate text-base leading-relaxed mb-8">
      Terima kasih. Saya akan hubungi anda dalam masa <strong>24 jam</strong> untuk schedule sesi audit anda.
    </p>

    <!-- Next steps -->
    <div class="bg-white border border-sand rounded-xl p-6 text-left mb-8 space-y-4">
      <h2 class="font-semibold text-coal text-sm uppercase tracking-wide">Apa berlaku seterusnya</h2>
      <div class="flex items-start gap-3">
        <span class="w-6 h-6 rounded-full bg-sage text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">1</span>
        <p class="text-slate text-sm">Semak e-mel anda untuk resit pembayaran dari ToyyibPay — dalam masa beberapa minit.</p>
      </div>
      <div class="flex items-start gap-3">
        <span class="w-6 h-6 rounded-full bg-sage text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">2</span>
        <p class="text-slate text-sm">Saya akan WhatsApp anda dalam <strong>24 jam</strong> untuk hantar borang pre-audit dan confirm jadual.</p>
      </div>
      <div class="flex items-start gap-3">
        <span class="w-6 h-6 rounded-full bg-sage text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">3</span>
        <p class="text-slate text-sm">Sesi audit akan dilakukan dalam masa <strong>3–5 hari bekerja</strong> selepas borang lengkap.</p>
      </div>
    </div>

    <!-- WhatsApp nudge -->
    <a href="https://wa.me/60122541050?text=Hi%20Najmi%2C%20saya%20baru%20buat%20payment%20untuk%20Audit%20Lite.%20Nama%20saya%3A%20"
       target="_blank" rel="noopener"
       class="inline-block w-full bg-sage text-white font-semibold py-3.5 px-6 rounded-lg text-sm hover:bg-green-600 transition-colors mb-4">
      Hantar WhatsApp Sekarang
    </a>
    <p class="text-slate/50 text-xs">Atau tunggu — saya akan hubungi anda dalam 24 jam.</p>

  </main>

</body>
</html>
