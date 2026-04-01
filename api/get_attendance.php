<?php
require 'config.php';

$res = $conn->query("SELECT * FROM attendance ORDER BY id DESC LIMIT 100");

$attendance = [];
while ($row = $res->fetch_assoc()) {
    $attendance[] = $row;
}

echo json_encode(["success" => true, "attendance" => $attendance]);
?>