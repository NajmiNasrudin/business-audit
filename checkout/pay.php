<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$product_id    = trim($_POST['product_id']    ?? '');
$name          = trim($_POST['name']          ?? '');
$email         = trim($_POST['email']         ?? '');
$phone         = trim($_POST['phone']         ?? '');
$business_name = trim($_POST['business_name'] ?? '');
$business_type = trim($_POST['business_type'] ?? '');

if (!isset($PRODUCTS[$product_id]) || !$PRODUCTS[$product_id]['available']) {
    die('Produk tidak sah.');
}

$product = $PRODUCTS[$product_id];

if (!$name || !$email || !$phone || !$business_name) {
    header('Location: index.php?error=incomplete');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: index.php?error=email');
    exit;
}

$order_ref    = strtoupper(substr($product_id, 0, 2)) . '-' . date('ymdHi') . '-' . rand(100, 999);
$amount_cents = $product['price'] * 100;
$phone_clean  = preg_replace('/\D/', '', $phone);

$payload = [
    'brand_id' => CHIP_BRAND_ID,
    'client'   => [
        'email'     => $email,
        'full_name' => $name,
        'phone'     => $phone_clean,
    ],
    'purchase' => [
        'currency' => 'MYR',
        'products' => [[
            'name'     => $product['name'] . ' — ' . $business_name,
            'price'    => $amount_cents,
            'quantity' => 1,
        ]],
        'notes' => 'Bisnes: ' . $business_name . ($business_type ? ' (' . $business_type . ')' : '') . ' | Ref: ' . $order_ref,
    ],
    'reference'        => $order_ref,
    'due'              => time() + 86400,
    'success_redirect' => SITE_URL . '/thank-you.php',
    'failure_redirect' => SITE_URL . '/cancel.php',
    'success_callback' => SITE_URL . '/callback.php',
    'send_receipt'     => true,
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, CHIP_BASE_URL . '/purchases/');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . CHIP_SECRET_KEY,
    'Content-Type: application/json',
]);
$response   = curl_exec($ch);
$curl_error = curl_error($ch);
curl_close($ch);

if ($curl_error || !$response) {
    error_log('Chip curl error: ' . $curl_error);
    header('Location: cancel.php?reason=api_error');
    exit;
}

$result = json_decode($response, true);

if (!isset($result['checkout_url'])) {
    error_log('Chip error: ' . $response);
    header('Location: cancel.php?reason=bill_error');
    exit;
}

$log_row = [
    date('Y-m-d H:i:s'),
    $order_ref,
    $result['id'] ?? '',
    $product['name'],
    'RM' . $product['price'],
    $name,
    $email,
    $phone,
    $business_name,
    $business_type,
    'PENDING',
];

$log_line = implode(',', array_map(fn($v) => '"' . str_replace('"', '""', $v) . '"', $log_row)) . "\n";

if (!file_exists(LOG_FILE)) {
    file_put_contents(LOG_FILE, '"Date","Order Ref","Purchase ID","Product","Amount","Name","Email","Phone","Business","Type","Status"' . "\n");
}
file_put_contents(LOG_FILE, $log_line, FILE_APPEND | LOCK_EX);

header('Location: ' . $result['checkout_url']);
exit;
