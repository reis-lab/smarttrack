<?php
require 'config.php';

$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'];
$password = $data['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    echo json_encode([
        "success" => true,
        "user" => $user
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid credentials"
    ]);
}
