<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }

$idx    = (int)($_POST['idx']    ?? -1);
$status = trim($_POST['status']  ?? '');
$allowed = ['Baru', 'Dah Contact', 'Nak Proceed', 'Tak Nak'];

if ($idx < 0 || !in_array($status, $allowed)) {
    http_response_code(422);
    echo json_encode(['error' => 'Invalid']);
    exit;
}

$file = __DIR__ . '/prospects.csv';
if (!file_exists($file)) { http_response_code(404); exit; }

$rows = [];
if (($fh = fopen($file, 'r')) !== false) {
    $headers = fgetcsv($fh);
    while (($row = fgetcsv($fh)) !== false) {
        $rows[] = $row;
    }
    fclose($fh);
}

// Find Status column index
$statusCol = array_search('Status', $headers);
if ($statusCol === false) {
    http_response_code(500);
    echo json_encode(['error' => 'No Status column']);
    exit;
}

if (!isset($rows[$idx])) {
    http_response_code(404);
    echo json_encode(['error' => 'Row not found']);
    exit;
}

$rows[$idx][$statusCol] = $status;

// Rewrite CSV
$out = fopen($file, 'w');
fputcsv($out, $headers);
foreach ($rows as $row) fputcsv($out, $row);
fclose($out);

header('Content-Type: application/json');
echo json_encode(['ok' => true]);
