<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();

if (!isset($_SESSION['admin-login'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $department = $_POST['department'];
    $sql = "INSERT INTO department_tbl (department) VALUES ('$department')";
    $query = $conn->query($sql);
    
    if ($query) {
        echo json_encode(['status' => 'success', 'message' => 'Department added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error adding department: ' . $conn->error]);
    }
    exit();
}
?>


