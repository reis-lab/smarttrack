<?php
require 'config.php';

$data = json_decode(file_get_contents("php://input"), true);

$rfid   = $data['rfid'];
$name   = $data['name'];
$grade  = $data['grade'];
$phone  = $data['parentPhone'];
$gender = $data['gender'];
$address = $data['address'] ?? '';
$school_id = 1;

$stmt = $conn->prepare("INSERT INTO students (school_id, rfid, name, grade, parent_phone, gender, address, suspended) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
$stmt->bind_param("issssssi", $school_id, $rfid, $name, $grade, $phone, $gender, $address);

if ($stmt->execute()) {
    echo json_encode(["success"=>true]);
} else {
    echo json_encode(["success"=>false, "error"=>$stmt->error]);
}
$stmt->close();
?>