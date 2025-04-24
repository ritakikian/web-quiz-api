<?php
header("Content-Type: application/json");
include "db.php";

$action = $_POST["action"] ?? "";

if ($action == "create") {
    $title = $_POST["title"] ?? "";
    $created_by = $_POST["created_by"] ?? "";

    if ($title && $created_by) {
        $sql = "INSERT INTO quizzes (title, created_by) VALUES ('$title', '$created_by')";
        $result = mysqli_query($conn, $sql);
        echo json_encode(["success" => $result, "message" => $result ? "Quiz created" : "Failed to create quiz"]);
    } else {
        echo json_encode(["success" => false, "message" => "Title and creator required"]);
    }
    exit;
}

if ($action == "get_all") {
    $res = mysqli_query($conn, "SELECT * FROM quizzes");
    $quizzes = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $quizzes[] = $row;
    }
    echo json_encode(["success" => true, "quizzes" => $quizzes]);
    exit;
}

if ($action == "edit") {
    $id = $_POST["id"] ?? "";
    $title = $_POST["title"] ?? "";

    if ($id && $title) {
        $res = mysqli_query($conn, "UPDATE quizzes SET title = '$title' WHERE id = '$id'");
        echo json_encode(["success" => $res, "message" => $res ? "Quiz updated" : "Failed to update"]);
    } else {
        echo json_encode(["success" => false, "message" => "ID and title required"]);
    }
    exit;
}

if ($action == "delete") {
    $id = $_POST["id"] ?? "";

    if ($id) {
        $res = mysqli_query($conn, "DELETE FROM quizzes WHERE id = '$id'");
        echo json_encode(["success" => $res, "message" => $res ? "Quiz deleted" : "Failed to delete"]);
    } else {
        echo json_encode(["success" => false, "message" => "ID required"]);
    }
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid action"]);
?>
