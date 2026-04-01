<?php
require 'config.php';

$res = $conn->query("SELECT last_seen FROM esp_status WHERE id=1");

if ($res->num_rows == 0) {
    echo json_encode(["online"=>false]);
    exit;
}

$row = $res->fetch_assoc();

$last = strtotime($row['last_seen']);
$now = time();

$online = ($now - $last) < 10; // 10 sec timeout

echo json_encode(["online"=>$online]);
?>