<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();

if (!isset($_SESSION['admin-login'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department = $_POST['department'];
    $employee = $_POST['employee'];
    $tasktitle = $_POST['tasktitle'];
    $taskdescription = $_POST['taskdescription'];
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];
    $sql = "INSERT INTO task_tbl (department, employee, task, taskdescription, startdate, enddate) 
            VALUES ('$department', '$employee', '$tasktitle', '$taskdescription', '$startdate', '$enddate')";

    if ($conn->query($sql)) {
        echo 'Task assigned successfully!';
    } else {
        echo 'Error: ' . $conn->error;
    }
}
?>
