<?php
$host = "dpg-d76ki1n5r7bs73c8ncqg-a.ohio-postgres.render.com";
$db   = "smarttrack_7xju";
$user = "smarttrack_7xju_user";
$pass = "graqwFNS1QcIfJHOVByOWet1IWxacPan";
$port = "5432";

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "error" => "DB connection failed"
    ]);
    exit;
}
?>
