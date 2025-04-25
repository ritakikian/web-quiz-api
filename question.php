<?php
header("Content-Type: application/json");
include "db.php";

$action = $_POST["action"] ?? "";

if ($action == "create") {
    $quiz_id = $_POST["quiz_id"] ?? "";
    $question = $_POST["question"] ?? "";
    $opt1 = $_POST["opt1"] ?? "";
    $opt2 = $_POST["opt2"] ?? "";
    $opt3 = $_POST["opt3"] ?? "";
    $correct = $_POST["correct"] ?? "";

    if ($quiz_id && $question && $opt1 && $opt2 && $opt3 && $correct !== "") {
        mysqli_query($conn, "INSERT INTO questions (quiz_id, question_text) VALUES ('$quiz_id', '$question')");
        $qid = mysqli_insert_id($conn);

        mysqli_query($conn, "INSERT INTO options (question_id, option_text, is_correct) VALUES ('$qid', '$opt1', '".($correct == 1 ? 1 : 0)."')");
        mysqli_query($conn, "INSERT INTO options (question_id, option_text, is_correct) VALUES ('$qid', '$opt2', '".($correct == 2 ? 1 : 0)."')");
        mysqli_query($conn, "INSERT INTO options (question_id, option_text, is_correct) VALUES ('$qid', '$opt3', '".($correct == 3 ? 1 : 0)."')");

        echo json_encode(["success" => true, "message" => "Question created"]);
    } else {
        echo json_encode(["success" => false, "message" => "All fields required"]);
    }
    exit;
}

if ($action == "get_by_quiz") {
    $quiz_id = $_POST["quiz_id"] ?? "";

    if ($quiz_id) {
        $questions = [];
        $qres = mysqli_query($conn, "SELECT * FROM questions WHERE quiz_id = '$quiz_id'");

        while ($q = mysqli_fetch_assoc($qres)) {
            $qid = $q["id"];
            $opts = [];
            $ores = mysqli_query($conn, "SELECT * FROM options WHERE question_id = '$qid'");
            while ($o = mysqli_fetch_assoc($ores)) {
                $opts[] = $o;
            }
            $q["options"] = $opts;
            $questions[] = $q;
        }

        echo json_encode(["success" => true, "questions" => $questions]);
    } else {
        echo json_encode(["success" => false, "message" => "Quiz ID required"]);
    }
    exit;
}

if ($action == "delete") {
    $id = $_POST["id"] ?? "";

    if ($id) {
        mysqli_query($conn, "DELETE FROM options WHERE question_id = '$id'");
        $res = mysqli_query($conn, "DELETE FROM questions WHERE id = '$id'");
        echo json_encode(["success" => $res, "message" => $res ? "Deleted" : "Delete failed"]);
    } else {
        echo json_encode(["success" => false, "message" => "ID required"]);
    }
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid action"]);
?>