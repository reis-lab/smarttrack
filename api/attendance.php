<?php
    require 'config.php';
ini_set('display_errors', 0);

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['rfid'])) {
    echo json_encode(["success"=>false,"error"=>"RFID missing"]);
    exit;
}

$rfid = $conn->real_escape_string($data['rfid']);

$res = $conn->query("SELECT * FROM students WHERE rfid='$rfid'");
if ($res->num_rows == 0) {
    echo json_encode(["success"=>false,"error"=>"Student not found"]);
    exit;
}

$student = $res->fetch_assoc();

$conn->query("INSERT INTO attendance (rfid, name) VALUES ('{$student['rfid']}', '{$student['name']}')");
$conn->query("REPLACE INTO latest_scan (id, rfid, name) VALUES (1, '{$student['rfid']}', '{$student['name']}')");

echo json_encode([
    "success" => true,
    "name" => $student['name'],
    "suspended" => (int)$student['suspended']
]);
?>