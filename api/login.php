<?php
header('Content-Type: application/json');
require 'config.php';

$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

$res = $conn->query("SELECT * FROM users WHERE email='$username'");

if ($res->num_rows == 0) {
    echo json_encode(["success"=>false, "message"=>"User not found"]);
    exit;
}

$user = $res->fetch_assoc();

if (!password_verify($password, $user['password'])) {
    echo json_encode(["success"=>false, "message"=>"Wrong password"]);
    exit;
}

// ✅ RETURN DATA
echo json_encode([
    "success" => true,
    "schoolName" => $user['school_name'],
    "adminName" => $user['admin_name']
]);