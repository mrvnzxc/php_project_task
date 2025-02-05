<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['task_id'];


    $query = "SELECT * FROM task_tbl WHERE id = $taskId";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
        $sqlInsert = "INSERT INTO confirm_task_tbl (department, employee, task, taskdescription, startdate, enddate, remarks)
                      VALUES ('{$task['department']}', '{$task['employee']}', '{$task['task']}', '{$task['taskdescription']}', '{$task['startdate']}', '{$task['enddate']}', 'Did Not Finish')";
        $conn->query($sqlInsert);


        $sqlDelete = "DELETE FROM task_tbl WHERE id = $taskId";
        $conn->query($sqlDelete);

        echo "Task moved successfully";
    } else {
        echo "Task not found";
    }
}
?>
