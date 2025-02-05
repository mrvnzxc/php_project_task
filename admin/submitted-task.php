<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();
if (!isset($_SESSION['admin-login'])) {
    header('Location: index.php');
    exit();
}

$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$recordsPerPage = 10;  
$offset = ($currentPage - 1) * $recordsPerPage;

//QUERY TO SELECT ALL ROWS FROM finish_task_tbl WHERE AS I CONCAT THE fname and lname for BETTER PRESENTATION
$sql = "SELECT finish_task_tbl.*, 
               department_tbl.department AS department,
               CONCAT(employee_tbl.fname, ' ', employee_tbl.lname) AS full_name
        FROM finish_task_tbl
        LEFT JOIN department_tbl ON finish_task_tbl.department = department_tbl.id 
        LEFT JOIN employee_tbl ON finish_task_tbl.employee = employee_tbl.id 
        WHERE finish_task_tbl.task LIKE '%$searchTerm%' OR finish_task_tbl.taskdescription LIKE '%$searchTerm%'
        OR finish_task_tbl.startdate LIKE '%$searchTerm%' OR finish_task_tbl.enddate LIKE '%$searchTerm%' 
        OR finish_task_tbl.submitdate LIKE '%$searchTerm%' OR finish_task_tbl.remarks LIKE '%$searchTerm%' 
        OR department_tbl.department LIKE '%$searchTerm%' OR CONCAT(employee_tbl.fname, ' ', employee_tbl.lname) LIKE '%$searchTerm%'
        ORDER BY finish_task_tbl.id DESC  
        LIMIT $offset, $recordsPerPage";

$result = $conn->query($sql);

$sqlCount = "SELECT COUNT(*) AS total FROM finish_task_tbl 
             LEFT JOIN department_tbl ON finish_task_tbl.department = department_tbl.id 
             LEFT JOIN employee_tbl ON finish_task_tbl.employee = employee_tbl.id 
             WHERE finish_task_tbl.task LIKE '%$searchTerm%' OR finish_task_tbl.taskdescription LIKE '%$searchTerm%'
        OR finish_task_tbl.startdate LIKE '%$searchTerm%' OR finish_task_tbl.enddate LIKE '%$searchTerm%' 
        OR finish_task_tbl.submitdate LIKE '%$searchTerm%' OR finish_task_tbl.remarks LIKE '%$searchTerm%' 
        OR department_tbl.department LIKE '%$searchTerm%' OR CONCAT(employee_tbl.fname, ' ', employee_tbl.lname) LIKE '%$searchTerm%'
        ORDER BY finish_task_tbl.id DESC  ";
$countResult = $conn->query($sqlCount);
$rowCount = $countResult->fetch_assoc();
$totalRecords = $rowCount['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

function calculateDaysRemaining($enddate, $remarks) {
    if ($remarks === 'Completed' || $remarks === 'Abandoned') {
        return 'ENDED';
    }
    $now = new DateTime();
    $end = new DateTime($enddate);
    if ($now >= $end) {
        return 'ENDED';
    }
    $interval = $now->diff($end);
    return $interval->days . ' days ' . $interval->h . ' hours remaining';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submitted Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        #customAlertModal .modal-header {
            background-color: #17a2b8;
            color: white;
        }

        #customAlertModal .modal-footer .btn-dark {
            background-color: #343a40;
            color: white;
        }

        #customAlertModal .modal-footer .btn-info {
            background-color: #17a2b8;
            color: white;
        }
        #returnAlertModal .modal-header {
            background-color: #17a2b8;
            color: white;
        }

        #returnAlertModal .modal-footer .btn-dark {
            background-color: #343a40;
            color: white;
        }

        #returnAlertModal .modal-footer .btn-info {
            background-color: #17a2b8;
            color: white;
        }
        .swal2-popup {
            font-family: 'Arial', sans-serif;
        }

        .swal2-popup {
            width: 400px !important;
            padding: 20px !important;
        }

        .swal2-confirm {
            background-color: #4CAF50 !important;
            color: white !important;
        }

        .swal2-cancel {
            background-color: #f44336 !important;
            color: white !important;
        }

        .swal2-title {
            font-size: 24px !important;
            color: #333 !important;
        }

        .swal2-html-container {
            font-size: 16px !important;
            color: #666 !important;
        }
    </style>
</head>
<body>
    <?php include('/xampp/htdocs/task/includes/navbar.php'); ?>
    <div class="container mt-3 min-vh-100">
        <div class="card shadow-lg">
            <div class="card-header bg-dark text-info">
                <h4 class = "mt-2">Submitted Tasks</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12 mb-3">
                        <form method="POST" class="d-inline">
                            <input type="text" name="search" class="form-control d-inline border border-dark" style="width: auto;" placeholder="Search Task">
                            <button type="submit" class="btn btn-dark text-info mb-1">Search</button>
                        </form>
                    </div>
                    <!-- TABLE DIV STARTS HERE -->
                    <div class="col-12">
                        <table class="table table-bordered table-striped table-light display mr-10" id = "taskTable" style = "width: 100%">
                            <thead class="thead-dark text-center">
                                <tr class = "table-dark" style = "font-size: 12px;">
                                    <th class = "text-center">ID</th>
                                    <th class = "text-center">Department</th>
                                    <th class = "text-center">Employee Name</th>
                                    <th class = "text-center">Task Title</th>
                                    <th class = "text-center">Task Description</th>
                                    <th class = "text-center">Start Date</th>
                                    <th class = "text-center">End Date</th>
                                    <th class = "text-center">Submitted Date</th>
                                    <th class = "text-center">Remarks</th>
                                    <th class = "text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <?php $daysRemaining = calculateDaysRemaining($row['enddate'], $row['remarks']); ?>
                                        <tr style = "font-size: 12px;"> 
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo $row['department']; ?></td>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['task']; ?></td>
                                            <td><?php echo $row['taskdescription']; ?></td>
                                            <td><?php echo date('F j, Y', strtotime($row['startdate'])); ?></td>
                                            <td><?php echo date('F j, Y', strtotime($row['enddate'])); ?></td>
                                            <td><?php echo date('F j, Y', strtotime($row['submitdate'])); ?></td>
                                            <td><?php echo $row['remarks']; ?></td>
                                            <td class="text-center">
                                                <div class="btn btn-group">
                                                    <button class="btn btn-transparent btn-sm dropdown-toggle" 
                                                            type="button" 
                                                            data-bs-toggle="dropdown" 
                                                            aria-expanded="false" 
                                                            style="border: 1px solid black; border-radius: 15px; color: black;">
                                                        View Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="#" 
                                                            class="dropdown-item"
                                                            data-id="<?php echo $row['id']; ?>"
                                                            onclick="showSweetAlert(this);" 
                                                            title="Confirm">
                                                                <i class="fa-solid fa-check-double"></i> Confirm Task
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#"
                                                            class="dropdown-item"
                                                            data-id="<?php echo $row['id']; ?>"
                                                            onclick="returnCustomAlert(this);" 
                                                            title="Return">
                                                                <i class="fa-solid fa-angles-left"></i> Return Task
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#"
                                                            class="dropdown-item"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#commentsModal<?php echo $row['id']; ?>">
                                                                <i class="bi bi-chat-fill"></i> View Comments
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="modal fade-custom" id="commentsModal<?php echo $row['id']; ?>" role="dialog" aria-labelledby="commentsModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="remarksModalLabel">
                                                                    View Comments for Task "<?php echo $row['task']; ?>"
                                                                </h5>
                                                                <button type="button" 
                                                                        class="btn-close" 
                                                                        data-bs-dismiss="modal" 
                                                                        aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="" method="POST">
                                                                    <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                                                                    <div class="form-group">
                                                                        <textarea class="form-control" 
                                                                                id="comments" 
                                                                                name="comments" 
                                                                                rows="3" 
                                                                                placeholder="<?php echo $row['comments']; ?>" 
                                                                                readonly></textarea>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- TABLE DIV ENDS HERE -->

                    <!-- PAGINATION STARTS HERE -->
                    <div class="col-12 text-center mt-3">
                        <p>Showing <?php echo ($offset + 1) . ' to ' . min($offset + $recordsPerPage, $totalRecords) . ' of ' . $totalRecords . ' entries'; ?></p>
                        <ul class="pagination justify-content-center">
                            <li class="page-item bg-dark text-info <?php if ($currentPage == 1) echo 'disabled'; ?>">
                                <a class="page-link bg-dark text-info" href="?page=<?php echo $currentPage - 1; ?>">Previous</a>
                            </li>
                            <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                                <li class="page-item bg-dark text-info<?php if ($page == $currentPage) echo ' active'; ?>">
                                    <a class="page-link bg-dark text-info" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item bg-dark text-info<?php if ($currentPage == $totalPages) echo 'disabled'; ?>">
                                <a class="page-link bg-dark text-info" href="?page=<?php echo $currentPage + 1; ?>">Next</a>
                            </li>
                        </ul>
                    </div>
                    <!-- PAGINATION ENDS HERE --> 
                </div>
            </div>
        </div>
    </div>
<script>
$(document).ready(function() {
    $('#taskTable').DataTable({
        paging: false,        
        searching: false,     
        ordering: true,       
        info: false,          
        lengthChange: false,  
        columnDefs: [
            { targets: 9, orderable: false }
        ]
    });
});

function showSweetAlert(button) {
    const taskId = button.getAttribute('data-id');
    const submitLink = `confirm-task.php?id=${taskId}`;

    Swal.fire({
        title: 'Confirm Task Submission',
        text: 'Are you sure you want to confirm this task?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = submitLink;
        }
    });
}

 function returnCustomAlert(button) {
     
     const taskId = button.getAttribute('data-id');
     const submitLink = `return-task.php?id=${taskId}`;

     
     Swal.fire({
         title: 'Return Task Submission',
         text: 'Are you sure you want to return this task?',
         icon: 'question',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Return',
         cancelButtonText: 'Cancel'
     }).then((result) => {
         if (result.isConfirmed) {
             window.location.href = submitLink;
         }
     })
 }
</script>
</body>
<script src="js/comments.js"></script>
</html>
