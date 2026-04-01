<?php
// ====================== SMARTTRACK config.php (ROBUST) ======================

$host     = 'sql100.byetcluster.com';
$db_user  = 'ezyro_41552429';
$db_pass  = '068e9dc4181';
$db_name  = 'ezyro_41552429_smarttrack';


ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => "DB Connection Failed"
    ]);
    exit;
}

$conn->set_charset("utf8mb4");

// Auto set JSON header for all API files
header("Content-Type: application/json; charset=utf-8");
?>