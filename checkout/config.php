<?php
define('CHIP_SECRET_KEY', 'g3q6sXUPhBHtO0lfHu6INYMOOeQkyVlO-wKTDV86_3Fa8pZBBTcFKPlT2KFFEHaZQje9s8Tr_wo6sVGTY03yqg==');
define('CHIP_BRAND_ID',   'fac78281-025a-483b-a8e2-627f052f86aa');
define('CHIP_BASE_URL',   'https://gate.chip-in.asia/api/v1');
define('SITE_URL',        'https://bizbuddyhq.com/checkout');
define('LOG_FILE',        __DIR__ . '/orders.csv');

$PRODUCTS = [
    'audit-lite' => [
        'id'          => 'audit-lite',
        'name'        => 'Audit Lite',
        'price'       => 697,
        'description' => 'Remote diagnostic bisnes anda — 5 pillar framework, report PDF, action plan 90 hari.',
        'available'   => true,
    ],
    'audit-standard' => [
        'id'          => 'audit-standard',
        'name'        => 'Audit Standard',
        'price'       => 1797,
        'description' => 'Half-day onsite atau full remote deep-dive — observation operasi, report PDF, 1on1 coaching call.',
        'available'   => true,
    ],
    'audit-pro' => [
        'id'          => 'audit-pro',
        'name'        => 'Audit Pro',
        'price'       => 2997,
        'description' => 'Full-day onsite + 30-day follow-up support — comprehensive diagnostic, 1on1 coaching + 1 staff, WhatsApp access.',
        'available'   => true,
    ],
    'coaching-zoom' => [
        'id'          => 'coaching-zoom',
        'name'        => 'Online Coaching',
        'price'       => 350,
        'description' => 'Coaching 1-on-1 dengan Najmi — 2 jam online. Audit 5 pillar business anda, priority ranking, follow-up notes.',
        'available'   => true,
        'group'       => 'coaching',
    ],
    'coaching-onsite' => [
        'id'          => 'coaching-onsite',
        'name'        => 'On-Site Coaching',
        'price'       => 1000,
        'description' => 'Coaching 1-on-1 face-to-face di Setia Alam — 3-4 jam. Bedah data anda deep, kopi break, follow-up notes.',
        'available'   => true,
        'group'       => 'coaching',
    ],
];
