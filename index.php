<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();

if (!isset($_SESSION['employee-login'])) {
    header('Location: login.php');
    exit();
}

$employeeId = $_SESSION['employee-id'];

$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$recordsPerPage = 10;  
$offset = ($currentPage - 1) * $recordsPerPage;

// QUERY TO SELECT SPECIFIC TASK BASED ON EMPLOYEE ID
$sql = "SELECT task_tbl.*, 
               department_tbl.department AS department,
               CONCAT(employee_tbl.fname, ' ', employee_tbl.lname) AS full_name
        FROM task_tbl
        LEFT JOIN department_tbl ON task_tbl.department = department_tbl.id 
        LEFT JOIN employee_tbl ON task_tbl.employee = employee_tbl.id 
        WHERE task_tbl.employee = '$employeeId' 
        AND task_tbl.task LIKE '%$searchTerm%' 
        LIMIT $offset, $recordsPerPage";

$result = $conn->query($sql);

// QUERY TO CHECK HOW MANY ROWS EXIST IN task_tbl FOR PAGINATION PURPOSES
$sqlCount = "SELECT COUNT(*) AS total FROM task_tbl 
             LEFT JOIN department_tbl ON task_tbl.department = department_tbl.id 
             LEFT JOIN employee_tbl ON task_tbl.employee = employee_tbl.id 
             WHERE task_tbl.employee = '$employeeId' 
             AND task_tbl.task LIKE '%$searchTerm%'";
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
    <title>Task List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <style>
        #customAlertModal .modal-header {
            background-color: #17a2b8;
            color: white;
        }
        #customAlertModal .modal-footer .btn-dark {
            background-color: #343a40;
            color: white;
        }
        .errorContainer {
            height: 40px;
            width: 100%;
            margin: 10px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            border: 1px solid red;
            background-color: #f8d7da;
            color: red;
            display: none;
        }
        .successContainer {
            height: 40px;
            width: 100%;
            margin: 10px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            border: 1px solid green;
            background-color: #d4edda;
            color: green;
            display: none;
        }
        .btn-close-custom {
            filter: invert(48%) sepia(81%) 
            saturate(1853%) hue-rotate(157deg);
        }
    </style>
</head>
<body>
    <?php include('employeenavbar.php'); ?>

    <div class="container mt-3 min-vh-100">
        <div class="card shadow-lg">
            <div class="card-header text-center bg-dark text-info">
                <h4>Task List</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <form method="POST" class="d-inline">
                            <input type="text" name="search" class="form-control d-inline border border-dark" style="width: auto;" placeholder="Search Task">
                            <button type="submit" class="btn btn-dark mb-1 text-info">Search</button>
                        </form>
                    </div>
                </div>

                <table class="table table-bordered table-light table-striped display" id="mytask" style="width: 100%;">
                    <thead class="table-dark" style="font-size: 12px;">
                        <tr>
                            <th>Department</th>
                            <th>Employee Name</th>
                            <th>Task Title</th>
                            <th>Task Description</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Days Remaining</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr style="font-size: 12px;">
                                    <td><?php echo $row['department']; ?></td>
                                    <td><?php echo $row['full_name']; ?></td>
                                    <td><?php echo $row['task']; ?></td>
                                    <td><?php echo $row['taskdescription']; ?></td>
                                    <td><?php echo date('F j, Y h:i:s a', strtotime($row['startdate'])); ?></td>
                                    <td><?php echo date('F j, Y h:i:s a', strtotime($row['enddate'])); ?></td>
                                    <td class="days-remaining" 
                                        data-startdate="<?php echo $row['startdate']; ?>" 
                                        data-enddate="<?php echo $row['enddate']; ?>">Loading...</td>
                                    <td class="text-center">
                                        <span><?php echo $row['remarks'] ?: 'No remarks'; ?></span>
                                        <button class="btn btn-dark btn-sm open-btn text-info" modal-id="remarksModal<?php echo $row['id']; ?>">Edit Remarks</button>
                                        
                                        <!-- Remarks Modal -->
                                        <div class="modal fade-custom" id="remarksModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-dark text-info">
                                                        <h5 class="modal-title">Edit Remarks for Task "<?php echo $row['task']; ?>"</h5>
                                                        <button type="button"   class="close-btn btn-close btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true"></span>
                                                        </button>
                                                        </div>
                                                    <div class="modal-body">
                                                    <div id="responseMessage" class="errorContainer"></div>
                                                        <form id="updateRemarksForm" action="task-remarks.php" method="POST">
                                                            <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                                                            <div class="form-group text-start">
                                                                <label for="remarks" class = "d-inline">Select Remark</label>
                                                                <select class="form-control mt-2" id="remarks" name="remarks" required>
                                                                    <option value="">Choose...</option>
                                                                    <option value="Haven't Started">Haven't Started</option>
                                                                    <option value="In Progress">In Progress</option>
                                                                    <option value="Completed">Completed</option>
                                                                    <option value="Abandoned" class = "text-danger">Abandoned</option>
                                                                </select>
                                                                <div class="alert alert-danger text-center mt-2 abandoned pt-n5 pb-n5" role="alert" >
                                                                    Once an abandoned task is set, it will automatically be deleted.
                                                                </div>
                                                            </div>
                                                            <div class="d-flex justify-content-end">
                                                                <button class="btn btn-dark text-info mt-2" type="submit">Update Remarks</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#" 
                                                class="btn btn-success btn-sm me-2 <?php echo (in_array($row['remarks'], ['Completed', 'Abandoned'])) ? '' : 'disabled'; ?>" 
                                                onclick="showCustomAlert(this);" 
                                                data-id="<?php echo $row['id']; ?>" 
                                                title="Submit">
                                                <i class="bi bi-check-circle"></i>
                                            </a>
                                            <button class="btn btn-success btn-sm open-btn" modal-id="commentsModal<?php echo $row['id']; ?>">
                                                <i class="bi bi-chat-fill"></i>
                                            </button>
                                        </div>
                                        <!-- COMMENTS MODAL STARTS HERE -->
                                        <div class="modal fade-custom" id="commentsModal<?php echo $row['id']; ?>" role="dialog" aria-labelledby="commentsModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-dark text-info">
                                                        <h5 class="modal-title" id="remarksModalLabel">Add Comments for Task "<?php echo $row['task']; ?>"</h5>
                                                        <button type="button" class="close-btn btn-close btn-close-custom " data-bs-dismiss="modal" aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="task-comments.php" method="POST">
                                                            <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                                                            <div class="form-group text-start">
                                                                <label for="comments">Add Comment</label>
                                                                <textarea id="comments" name="comments" class="form-control input-lg mt-2" placeholder="<?php echo $row['comments']; ?>" required></textarea>
                                                            </div>
                                                            <div class="d-flex justify-content-end">
                                                                <button type="submit" class="btn btn-dark text-info mt-2">Submit Comment</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>      
                                        <!-- COMMENTS MODAL ENDS HERE -->
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>

                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination Starts-->
                <div class="text-center mt-3">
                    <p>Showing <?php echo ($offset + 1) . ' to ' . min($offset + $recordsPerPage, $totalRecords) . ' of ' . $totalRecords . ' entries'; ?></p>
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php if ($currentPage == 1) echo 'disabled'; ?>">
                            <a class="page-link bg-dark text-info" href="?page=<?php echo $currentPage - 1; ?>">Previous</a>
                        </li>
                        <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                            <li class="page-item <?php if ($page == $currentPage) echo 'active'; ?>">
                                <a class="page-link bg-dark text-info" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php if ($currentPage == $totalPages) echo 'disabled'; ?>">
                            <a class="page-link bg-dark text-info" href="?page=<?php echo $currentPage + 1; ?>">Next</a>
                        </li>
                    </ul>
                </div>
                <!-- Pagination Ends -->

                <!-- CUSTOM ALERT SUBMIT TASK STARTS HERE  -->
                <div class="modal fade" id="customAlertModal" tabindex="-1" aria-labelledby="customAlertModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="customAlertModalLabel">Submit Task</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                    Are you sure you want to submit this task?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                                <a href="#" id="confirmSubmitBtn" class="btn btn-info">Confirm</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- CUSTOM ALERT SUBMIT TASKENDS HERE -->
            </div>
        </div>
    </div>
</body>

<script>
function startCountdown() {
    document.querySelectorAll('.days-remaining').forEach(element => {
        const startDate = new Date(element.getAttribute('data-startdate'));
        const endDate = new Date(element.getAttribute('data-enddate'));
        const remarks = element.getAttribute('data-remarks');

        if (remarks === 'Completed' || remarks === 'Abandoned') {
            element.innerText = 'ENDED';
            return;
        }

        function updateCountdown() {
            const now = new Date();
            if (now >= endDate) {
                element.innerText = 'Did Not Finish';
                return;
            }

            const totalDuration = endDate - startDate;
            const timeElapsed = now - startDate;
            const remainingTime = totalDuration - timeElapsed;

            const days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
            const hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

            if (remainingTime <= 3600000) { 
                element.classList.add('blink-red');
                element.innerHTML = `<span>${days}d</span>
                                <span>${hours}h</span>
                                <span>${minutes}m</span>
                                <span>${seconds}s</span>`;
            } else {
                element.classList.remove('blink-red');
                element.innerHTML = `<span style = "color: green;">${days}d</span>
                                    <span style="color: blue;">${hours}h</span>
                                    <span style="color: black;">${minutes}m</span>
                                    <span style="color: red;">${seconds}s</span>`;
            }
            
        }

        updateCountdown(); 
        setInterval(updateCountdown, 1000); 
    });
    const style = document.createElement('style');
    style.innerHTML = `
        .blink-red {
            color: red;
            animation: blink-red-animation 1s steps(2, start) infinite;
        }
        @keyframes blink-red-animation {
            50% {
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
}

document.addEventListener('DOMContentLoaded', startCountdown);
$(document).ready(function() {
    $('#mytask').DataTable({
        paging: false,        
        searching: false,     
        ordering: true,       
        info: false,          
        lengthChange: false,  
        columnDefs: [
            { targets: 8, orderable: false }
        ]
    });
});

function showCustomAlert(button) {
     
    const taskId = button.getAttribute('data-id');
    const submitLink = `submit-task.php?id=${taskId}`;

      
    document.getElementById('confirmSubmitBtn').setAttribute('href', submitLink);

      
    const customAlertModal = new bootstrap.Modal(document.getElementById('customAlertModal'));
    customAlertModal.show();
}

function remarksAlert(button){
    const taskId = button.getAttribute('data-id');
    const remarksLink = `task-remarks.php?id=${taskId}`;
    document.getElementById('confirmSubmitBtn').setAttribute('href', remarksLink);
    const remarksModal = new bootstrap.Modal(document.getElementById('remarksAlert'));
    remarksAlert.show();
}

document.getElementById('updateRemarksForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const responseMessage = document.getElementById('responseMessage');

    try {
        const response = await fetch('task-remarks.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        responseMessage.textContent = result.message;
        responseMessage.style.display = 'flex';

        if (result.success) {
            if (result.isAbandoned) {
                responseMessage.style.color = 'red'; 
            } else {
                responseMessage.className = 'successContainer'; 
            }
            setTimeout(() => window.location.href = 'index.php', 2000);
        } else {
            responseMessage.className = 'errorContainer'; 
        }
    } catch (error) {
        responseMessage.textContent = 'An error occurred while processing your request.';
        responseMessage.style.display = 'flex';
        responseMessage.className = 'errorContainer';
    }
});
</script>
<script src="admin/js/comments.js"></script>
</html>

