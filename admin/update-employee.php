<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $department = $_POST['department'];
    $username = $_POST['username'];

    if (!empty($id) && !empty($fname) && !empty($lname) && !empty($department) && !empty($username)) {
        $checkUsername = "SELECT * FROM employee_tbl WHERE username = '$username' AND id != '$id'";
        $result = $conn->query($checkUsername);
        
        if ($result->num_rows > 0) {
            echo json_encode(['error' => 'Username already exists!']);
            exit();
        }

        $sql = "UPDATE employee_tbl 
                SET fname = '$fname', lname = '$lname', department = '$department', username = '$username' 
                WHERE id = '$id'";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => 'Employee updated successfully']);
            exit();
        } else {
            echo json_encode(['error' => 'Error updating record: ' . $conn->error]);
            exit();
        }
    } else {
        echo json_encode(['error' => 'All fields are required.']);
        exit();
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
    exit();
}
?>
