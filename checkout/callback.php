<?php
require_once __DIR__ . '/config.php';

$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data) {
    http_response_code(400);
    exit;
}

$purchase_id  = $data['id']                   ?? '';
$order_ref    = $data['reference']            ?? '';
$status       = $data['status']               ?? '';
$amount       = ($data['purchase']['total']   ?? 0) / 100;
$currency     = $data['purchase']['currency'] ?? 'MYR';
$client_name  = $data['client']['full_name']  ?? '';
$client_email = $data['client']['email']      ?? '';

$log_row = [
    date('Y-m-d H:i:s'),
    $order_ref,
    $purchase_id,
    $client_name,
    $client_email,
    $currency . number_format($amount, 2),
    strtoupper($status),
];

$log_line     = implode(',', array_map(fn($v) => '"' . str_replace('"', '""', $v) . '"', $log_row)) . "\n";
$callback_log = __DIR__ . '/payments.csv';

if (!file_exists($callback_log)) {
    file_put_contents($callback_log, '"Date","Order Ref","Purchase ID","Name","Email","Amount","Status"' . "\n");
}
file_put_contents($callback_log, $log_line, FILE_APPEND | LOCK_EX);

// On successful payment, push client to audit system
if (strtolower($status) === 'paid' && file_exists(LOG_FILE)) {
    $order = null;
    if (($fh = fopen(LOG_FILE, 'r')) !== false) {
        $headers = fgetcsv($fh);
        while (($row = fgetcsv($fh)) !== false) {
            if (isset($row[1]) && $row[1] === $order_ref) {
                $order = array_combine($headers, $row);
                break;
            }
        }
        fclose($fh);
    }

    if ($order) {
        $payload = json_encode([
            'business_name' => $order['Business'] ?? '',
            'owner_name'    => $order['Name']     ?? '',
            'email'         => $order['Email']    ?? '',
            'phone'         => $order['Phone']    ?? '',
            'business_type' => $order['Type']     ?? '',
            'product'       => $order['Product']  ?? 'Audit Lite',
            'order_ref'     => $order_ref,
        ]);

        $ch = curl_init('https://audit.bizbuddyhq.com/webhook/checkout');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-Webhook-Secret: bzb-checkout-webhook-2026',
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
}

http_response_code(200);
echo 'OK';
