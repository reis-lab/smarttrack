<?php
require 'config.php';

$res = $conn->query("SELECT rfid, name, suspended FROM students");

$students = [];
while ($row = $res->fetch_assoc()) {
    $students[] = $row;
}

echo json_encode([
    "students" => $students
]);
?>