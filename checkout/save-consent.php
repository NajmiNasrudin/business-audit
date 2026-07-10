<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://bizbuddyhq.com');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false]);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);
if (!$body) {
    http_response_code(400);
    echo json_encode(['ok' => false]);
    exit;
}

$log_file = __DIR__ . '/consent-log.csv';
$is_new   = !file_exists($log_file);

$row = [
    date('Y-m-d H:i:s'),
    $_SERVER['REMOTE_ADDR'] ?? '',
    $_SERVER['HTTP_USER_AGENT'] ?? '',
    $body['tier'] ?? '',
    $body['amount'] ?? '',
    $body['email'] ?? '',
    $body['policy_version'] ?? '',
];

$fp = fopen($log_file, 'a');
if ($fp) {
    if ($is_new) {
        fputcsv($fp, ['timestamp', 'ip', 'user_agent', 'tier', 'amount', 'email', 'policy_version']);
    }
    fputcsv($fp, $row);
    fclose($fp);
}

echo json_encode(['ok' => true]);
