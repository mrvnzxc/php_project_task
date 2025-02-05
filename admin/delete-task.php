<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();

if (!isset($_SESSION['admin-login'])) {
    header('Location: index.php');
    exit();
}
if (isset($_GET['id'])) {
    $taskId = $_GET['id'];
    $sql = "DELETE FROM task_tbl WHERE id = '$taskId'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.href = 'current-task.php';</script>";
    } else {
        echo "<script>alert('Error deleting task: " . $conn->error . "'); window.location.href = 'current-task.php';</script>";
    }
} else {
    header('Location: welcome.php');
}
?>
