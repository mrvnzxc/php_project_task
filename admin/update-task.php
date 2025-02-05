<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();

if (!isset($_SESSION['admin-login'])) {
    header('Location: index.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['taskId'];
    $task = $_POST['task'];
    $taskdescription = $_POST['taskdescription'];
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];
    if(!empty($taskId) && !empty($task) && !empty($taskdescription) && !empty($startdate) && !empty($enddate)) {
        $sql = "UPDATE task_tbl SET task = '$task', taskdescription = '$taskdescription', startdate = '$startdate', enddate = '$enddate' WHERE id = '$taskId'";
        if($conn->query($sql) === TRUE) {
            header('Location: current-task.php?success=updated');
            exit();
        }else{
            echo "Error updating task: " . $conn->error;
        }
    }else{
        echo "All fields are required";
    }
}else{
    header('Location: current-task.php');
    exit();
}
?>