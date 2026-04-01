<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require __DIR__ . '/config.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success"=>false,"message"=>"No data"]);
    exit;
}

$school   = $data['school_name'] ?? '';
$admin    = $data['admin_name'] ?? '';
$email    = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$school || !$admin || !$email || !$password) {
    echo json_encode(["success"=>false,"message"=>"Missing fields"]);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (school_name, admin_name, email, password) VALUES (?, ?, ?, ?)");

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("ssss", $school, $admin, $email, $hashedPassword);

if ($stmt->execute()) {
    echo json_encode(["success"=>true]);
} else {
    echo json_encode([
        "success"=>false,
        "message"=>$stmt->error
    ]);
}