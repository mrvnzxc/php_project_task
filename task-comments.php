<?php
include_once('database/dbconnect.php');
session_start();

if (!isset($_SESSION['employee-login'])) {
    header('Location: index.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $comment = $_POST['comments'];
    $task_id = intval($task_id);
    $comment = $conn->real_escape_string($comment);
    
    //QUERY TO INSERT COMMENTS INTO task_tbl
    $sql = "UPDATE task_tbl SET comments = '$comment' WHERE id = $task_id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Comments added successfully!'); window.location.href = 'index.php';</script>";
        exit();
    } else {
        echo "Error adding comments: " . $conn->error;
    }
}
$conn->close();
?>


