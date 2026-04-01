<?php
    require 'config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$uid = $data['rfid'] ?? '';

if (!$uid) {
    echo json_encode(["status"=>"error"]);
    exit;
}

$stmt = $conn->prepare("SELECT name FROM students WHERE rfid = ?");
$stmt->bind_param("s", $uid);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $student = $res->fetch_assoc();
    $conn->query("INSERT INTO attendance (rfid) VALUES ('$uid')");

    echo json_encode(["status" => "known", "name" => $student['name']]);
} else {
    file_put_contents("../data/last_uid.txt", $uid);
    echo json_encode(["status" => "new"]);
}
?>