<?php
include_once('database/dbconnect.php');
session_start();

if (!isset($_SESSION['employee-login'])) {
    header('Location: login.php');
    exit();
}
$employeeId = $_SESSION['employee-id'];
if (isset($_GET['id'])) {
    $taskId = $_GET['id'];

$queryCheck = "SELECT * FROM finish_task_tbl WHERE id = '$taskId' AND employee = '$employeeId'";
$resultCheck = $conn->query($queryCheck);

if ($resultCheck->num_rows > 0) {
    echo "This task has already been submitted.";
    exit();
}
$query = "SELECT * FROM task_tbl WHERE id = '$taskId' AND employee = '$employeeId'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $taskData = $result->fetch_assoc();


    $departmentId = $taskData['department'];
    $checkDepartment = "SELECT * FROM department_tbl WHERE id = '$departmentId'";
    $departmentResult = $conn->query($checkDepartment);

    if ($departmentResult->num_rows > 0) {

        //QUERY TO INSERT SUBMITTED TASK BY EMPLOYEE INTO finish_task_tbl
        $insertQuery = "INSERT INTO finish_task_tbl (task, taskdescription, startdate, enddate, employee, department, remarks, comments)
                        VALUES ('{$taskData['task']}', '{$taskData['taskdescription']}', 
                        '{$taskData['startdate']}', '{$taskData['enddate']}', 
                        '{$taskData['employee']}', '{$taskData['department']}', '{$taskData['remarks']}', '{$taskData['comments']}')";

        if ($conn->query($insertQuery)) {
             //QUERY TO DELETE SUBMITTED TASK BY EMPLOYEE INTO task_tbl
            $deleteQuery = "DELETE FROM task_tbl WHERE id = '$taskId' AND employee = '$employeeId'";
            $conn->query($deleteQuery);
            header("Location: index.php?status=task-submitted");
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Invalid task!";
    }
} else {
    echo "Task not found!";
}
}
?>
