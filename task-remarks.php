<?php
include_once('database/dbconnect.php');
session_start();

if (!isset($_SESSION['employee-login'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => 'Unknown error occurred.'];

    $task_id = intval($_POST['task_id']);
    $remarks = $conn->real_escape_string($_POST['remarks']);

    $fetchTaskQuery = "SELECT * FROM task_tbl WHERE id = $task_id";
    $taskResult = $conn->query($fetchTaskQuery);

    if ($taskResult && $taskResult->num_rows > 0) {
        $task = $taskResult->fetch_assoc();

        $updateQuery = "UPDATE task_tbl SET remarks = '$remarks' WHERE id = $task_id";
        if ($conn->query($updateQuery) === TRUE) {
            $response['success'] = true;
            $response['message'] = 'Remarks updated successfully!';

            if ($remarks === 'Abandoned') {
                $insertQuery = "INSERT INTO confirm_task_tbl (department, employee, task, taskdescription, startdate, enddate, remarks)
                                VALUES ('{$task['department']}', '{$task['employee']}', '{$task['task']}', '{$task['taskdescription']}', '{$task['startdate']}', '{$task['enddate']}', 'Abandoned')";
            
                if ($conn->query($insertQuery) === TRUE) {
                    $deleteQuery = "DELETE FROM task_tbl WHERE id = $task_id";
                    if ($conn->query($deleteQuery) === TRUE) {
                        $response['success'] = true;
                        $response['message'] = 'Task Abandoned Successfully!';
                        $response['isAbandoned'] = true; 
                    } else {
                        $response['success'] = false;
                        $response['message'] = 'Failed to delete task from task_tbl: ' . $conn->error;
                    }
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Failed to move task to confirm_task_tbl: ' . $conn->error;
                }
            }
            
        } else {
            $response['success'] = false;
            $response['message'] = 'Error updating remarks: ' . $conn->error;
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Task not found.';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    $conn->close();
    exit();
}
header('Content-Type: application/json');
echo json_encode([
    'success' => false,
    'message' => 'Invalid request method.'
]);
exit();
?>
