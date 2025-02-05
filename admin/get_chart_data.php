<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();

$month = isset($_GET['month']) ? $_GET['month'] : null;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10; 
$offset = ($page - 1) * $limit;


$query = "
    SELECT 
        CONCAT(e.fname, ' ', e.lname) AS employee_name,
        c.remarks,
        COUNT(*) AS task_count
    FROM confirm_task_tbl c
    JOIN employee_tbl e ON c.employee = e.id
";

if ($month) {
    $query .= " WHERE DATE_FORMAT(c.submitdate, '%Y-%m') = '$month'";
}


$query .= " GROUP BY c.employee, c.remarks LIMIT $limit OFFSET $offset";

$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$conn->close();

echo json_encode($data);
?>
