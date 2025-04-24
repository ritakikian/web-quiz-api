<?php
$host = "localhost";
$user = "root";  
$pass = "rootroot";           
$db   = "quiz_app";   

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "message" => "Database connection failed: " . $conn->connect_error
    ]));
}
?>
