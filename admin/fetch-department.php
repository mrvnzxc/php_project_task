<?php
header('Content-Type: application/json');
include_once('/xampp/htdocs/task/database/dbconnect.php');

$sql = "SELECT id, department FROM department_tbl";
$result = $conn->query($sql);

$departmentOptions = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $departmentOptions[] = $row;
    }
}

$conn->close();
echo json_encode($departmentOptions);
?>
