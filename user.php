<?php
header("Content-Type: application/json");

include "db.php";

$action  = $_POST["action"] ?? "";
$email   = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";

if ($action === "register") {
    if ($email === "" || $password === "") {
        echo json_encode(["success" => false, "message" => "Email and password required"]);
        exit;
    }

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        echo json_encode(["success" => false, "message" => "Email already taken"]);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $insert = mysqli_query($conn, "INSERT INTO users (email, password, role) VALUES ('$email', '$hashedPassword', 'user')");

    if ($insert) {
        echo json_encode(["success" => true, "message" => "User registered successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Registration failed"]);
    }

    exit;
}

if ($action === "login") {
    if ($email === "" || $password === "") {
        echo json_encode(["success" => false, "message" => "Email and password required"]);
        exit;
    }

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user["password"])) {
        echo json_encode([
            "success" => true,
            "message" => "Login successful",
            "user" => [
                "id" => $user["id"],
                "email" => $user["email"],
                "role" => $user["role"]
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid email or password"]);
    }

    exit;
}

echo json_encode(["success" => false, "message" => "Invalid action"]);
?>