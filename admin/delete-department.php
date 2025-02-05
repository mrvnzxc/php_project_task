<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();

if (!isset($_SESSION['admin-login'])) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $sql = "DELETE FROM department_tbl WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.href='department.php';</script>";
    } else {
        echo "<script>alert('Error deleting employee: " . $conn->error . "'); window.location.href='department.php';</script>";
    }
} else {
    echo "<script>alert('Department ID is not provided.'); window.location.href='department.php';</script>";
}
?>
