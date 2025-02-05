<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();

if (!isset($_SESSION['admin-login'])) {
    header('Location: index.php');
    exit();
}
if (isset($_GET['id'])) {
    $employeeid = $_GET['id'];
    $sql = "DELETE FROM employee_tbl WHERE id = '$employeeid'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.href='employee.php';</script>";
    } else {
        echo "<script>alert('Error deleting employee: " . $conn->error . "'); window.location.href = 'employee.php';</script>";
    }
} else {
    header('Location: employee.php');
}
?>
