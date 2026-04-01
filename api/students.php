<?php
    ini_set('display_errors', 0);
require 'config.php';

$res = $conn->query("SELECT * FROM students");

$data = [];

while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "success"=>true,
    "students"=>$data
]);
?>