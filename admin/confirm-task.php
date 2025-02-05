<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();

if (!isset($_SESSION['admin-login'])) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['id'])) {
    $taskId = $_GET['id'];

    $query = "SELECT task, taskdescription, startdate, enddate, employee, department, remarks FROM finish_task_tbl WHERE id = '$taskId'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $taskDetails = $result->fetch_assoc();

        $insertQuery = "INSERT INTO confirm_task_tbl (task, taskdescription, startdate, enddate, employee, department, remarks)
                        VALUES ('{$taskDetails['task']}', '{$taskDetails['taskdescription']}', '{$taskDetails['startdate']}',
                        '{$taskDetails['enddate']}','{$taskDetails['employee']}', '{$taskDetails['department']}','{$taskDetails['remarks']}')";

        if ($conn->query($insertQuery)) {
            $deleteQuery = "DELETE FROM finish_task_tbl WHERE id = '$taskId'";

            if ($conn->query($deleteQuery)) {
                header("Location: submitted-task.php?status=task-confirmed");
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
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
