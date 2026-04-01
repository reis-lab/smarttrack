<?php
header('Content-Type: application/json');
require 'config.php';

$data = json_decode(file_get_contents("php://input"), true);
$uid = $data['uid'] ?? '';

if ($uid) {
    // Save in database instead of file (works forever on Render)
    $conn->query("REPLACE INTO latest_scan (id, rfid, name) VALUES (1, '$uid', 'New Card')");
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
?>