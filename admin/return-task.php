<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();

if (!isset($_SESSION['admin-login'])) {
    header('Location: index.php');
    exit();
}
$taskId = $_GET['id']; 
if (isset($_GET['id'])) {
    //query to select the specific task based on id
    $query = "SELECT task, taskdescription, startdate, enddate, employee, department, comments FROM finish_task_tbl WHERE id = '$taskId'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $taskDetails = $result->fetch_assoc();
        $insertQuery = "INSERT INTO task_tbl (task, taskdescription, startdate, enddate,  employee, department, comments)
                        VALUES ('{$taskDetails['task']}', '{$taskDetails['taskdescription']}', '{$taskDetails['startdate']}',
                        '{$taskDetails['enddate']}','{$taskDetails['employee']}','{$taskDetails['department']}', '{$taskDetails['comments']}')";

        if ($conn->query($insertQuery)) {
            $deleteQuery = "DELETE FROM finish_task_tbl WHERE id = '$taskId'";
            $conn->query($deleteQuery);
            header("Location: submitted-task.php?status=task-returned");
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Task not found.";
    }
} else {
    echo "Invalid task ID.";
}
?>
