<?php
require 'config.php';

$res = $conn->query("SELECT * FROM students");

$students = [];

while ($row = $res->fetch_assoc()) {
    $students[] = $row;
}

echo json_encode([
    "success" => true,
    "students" => $students
]);