<?php
require 'config.php';
ini_set('display_errors', 0);

$res = $conn->query("SELECT rfid FROM students WHERE suspended=1");

$list = [];
while ($row = $res->fetch_assoc()) {
    $list[] = $row['rfid'];
}

echo json_encode(["success"=>true, "suspended"=>$list]);
?>