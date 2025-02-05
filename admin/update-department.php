<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();

if (!isset($_SESSION['admin-login'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $departmentId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $departmentName = isset($_POST['department']) ? $_POST['department'] : '';


    if (empty($departmentName)) {
        echo json_encode(['status' => 'error', 'message' => 'Department name cannot be empty.']);
        exit();
    }

    $departmentName = mysqli_real_escape_string($conn, $departmentName);
    $sql = "UPDATE department_tbl SET department = '$departmentName' WHERE id = $departmentId";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Department updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update department.']);
    }

    $conn->close();
}
?>

