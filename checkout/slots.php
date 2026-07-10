<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/config.php';

$config_file = __DIR__ . '/slots-config.json';
$limits = file_exists($config_file) ? json_decode(file_get_contents($config_file), true) : [];

// Count paid orders this month per product
$paid_this_month = [];
$payments_file = __DIR__ . '/payments.csv';
if (file_exists($payments_file) && ($fh = fopen($payments_file, 'r')) !== false) {
    $headers = fgetcsv($fh);
    $month = date('Y-m');
    while (($row = fgetcsv($fh)) !== false) {
        $data = array_combine($headers, $row);
        if (
            isset($data['Date'], $data['Status'], $data['Name']) &&
            strtoupper($data['Status']) === 'PAID' &&
            str_starts_with($data['Date'], $month)
        ) {
            // Match product from orders.csv by order ref
        }
    }
    fclose($fh);
}

// Use orders.csv which has Product column
$orders_file = __DIR__ . '/orders.csv';
$used = [];
if (file_exists($orders_file) && ($fh = fopen($orders_file, 'r')) !== false) {
    $headers = fgetcsv($fh);
    $month = date('Y-m');
    while (($row = fgetcsv($fh)) !== false) {
        $data = array_combine($headers, $row);
        // Only count this month's paid orders
        if (
            isset($data['Date'], $data['Status'], $data['Product']) &&
            strtoupper($data['Status']) === 'PAID' &&
            str_starts_with($data['Date'], $month)
        ) {
            // Map product name back to id
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

$result = [];
foreach ($PRODUCTS as $id => $product) {
    $limit = $limits[$id] ?? 0;
    $booked = $used[$id] ?? 0;
    $remaining = max(0, $limit - $booked);
    $result[$id] = [
        'limit'     => $limit,
        'booked'    => $booked,
        'remaining' => $remaining,
    ];
}

echo json_encode($result);
