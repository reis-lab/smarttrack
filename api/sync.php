<?php
    require 'config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['records'])) {
    foreach ($data['records'] as $r) {
        $rfid = $conn->real_escape_string($r['rfid'] ?? '');
        if ($rfid) {
            $conn->query("INSERT IGNORE INTO attendance (rfid) VALUES ('$rfid')");
        }
    }
}

echo json_encode(["success" => true]);
?>