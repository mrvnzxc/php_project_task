<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
header('Content-Type: application/json');


if (isset($_GET['departmentId'])) {
    $departmentId = intval($_GET['departmentId']); 

 
    $query = "SELECT id, fname, lname FROM employee_tbl WHERE department = $departmentId";
    $result = $conn->query($query);

    $employees = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $employees[] = $row;
        }
    }
    
    echo json_encode($employees);
} else {
    echo json_encode([]);
}

$conn->close();
?>