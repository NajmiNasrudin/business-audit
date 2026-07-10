<?php
header('Access-Control-Allow-Origin: https://bizbuddyhq.com');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$nama  = trim($_POST['nama']  ?? '');
$phone = trim($_POST['phone'] ?? '');
$bisnes = trim($_POST['bisnes'] ?? '');
$jenis = trim($_POST['jenis'] ?? '');

if (!$nama || !$phone || !$bisnes) {
    http_response_code(422);
    echo json_encode(['error' => 'Missing fields']);
    exit;
}

$log_file = __DIR__ . '/prospects.csv';
$row = [
    date('Y-m-d H:i:s'),
    $nama,
    $phone,
    $bisnes,
    $jenis,
    'Baru',
];

$line = implode(',', array_map(fn($v) => '"' . str_replace('"', '""', $v) . '"', $row)) . "\n";

if (!file_exists($log_file)) {
    file_put_contents($log_file, '"Tarikh","Nama","Phone","Bisnes","Jenis","Status"' . "\n");
}
file_put_contents($log_file, $line, FILE_APPEND | LOCK_EX);

echo json_encode(['ok' => true]);
