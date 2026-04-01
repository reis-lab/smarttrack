<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);

$file = "../data/last_uid.txt";

if (!file_exists($file)) {
    echo json_encode(["uid" => ""]);
    exit;
}

$uid = trim(file_get_contents($file));
echo json_encode(["uid" => $uid]);
?>