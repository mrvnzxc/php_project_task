<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();

if (!isset($_SESSION['admin-login'])) {
    header('Location: index.php');
    exit();
    
}
//PANG SEARCH NI DIRI
$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$recordsPerPage = 10;  
$offset = ($currentPage - 1) * $recordsPerPage;

$sql = "SELECT task_tbl.*, 
               department_tbl.department AS department,
               CONCAT(employee_tbl.fname, ' ', employee_tbl.lname) AS full_name
        FROM task_tbl
        LEFT JOIN department_tbl ON task_tbl.department = department_tbl.id 
        LEFT JOIN employee_tbl ON task_tbl.employee = employee_tbl.id 
        WHERE task_tbl.task LIKE '%$searchTerm%' 
        OR department_tbl.department LIKE '%$searchTerm%'
        OR employee_tbl.fname LIKE '%$searchTerm%'
        OR employee_tbl.lname LIKE '%$searchTerm%'
        OR task_tbl.taskdescription LIKE '%$searchTerm%'
        OR task_tbl.remarks LIKE '%$searchTerm%'
        OR task_tbl.startdate LIKE '%$searchTerm%'
        OR task_tbl.enddate LIKE '%$searchTerm%'
        ORDER BY task_tbl.id DESC
        LIMIT $offset, $recordsPerPage";
$result = $conn->query($sql);

//TAPOS KARI PANG COUNT FOR PAGINATION
$sqlCount = "SELECT COUNT(*) AS total FROM task_tbl
             LEFT JOIN department_tbl ON task_tbl.department = department_tbl.id 
             LEFT JOIN employee_tbl ON task_tbl.employee = employee_tbl.id 
        WHERE task_tbl.task LIKE '%$searchTerm%' 
        OR department_tbl.department LIKE '%$searchTerm%'
        OR employee_tbl.fname LIKE '%$searchTerm%'
        OR employee_tbl.lname LIKE '%$searchTerm%'
        OR task_tbl.taskdescription LIKE '%$searchTerm%'
        OR task_tbl.remarks LIKE '%$searchTerm%'
        OR task_tbl.startdate LIKE '%$searchTerm%'
        OR task_tbl.enddate LIKE '%$searchTerm%'";
$countResult = $conn->query($sqlCount);
$rowCount = $countResult->fetch_assoc();
$totalRecords = $rowCount['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Active List</title>

    <!-- DATATABLES CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- FONT AWESOME CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- BOOTSTRAP CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" 
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" 
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" 
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" 
    crossorigin="anonymous"></script>
    <!-- BOOTSTRAP CDN END -->

    <!-- SWEETALERT CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     
    <!-- JAVASCRIPT -->
    <script src="js/fetchemployee.js"></script>
    <style>
        #customAlertModal .modal-header {
            background-color: #17a2b8;
            color: white;
        }
        #customAlertModal .modal-footer .btn-dark {
            background-color: #343a40;
            color: white;
        }
        #customUpdateAlertModal .modal-header {
            background-color: #17a2b8; 
            color: white;
        }
        #customUpdateAlertModal .modal-footer .btn-dark {
            background-color: #343a40; 
            color: white;
        }
        #deleteAlertModal .modal-header {
            background-color: #17a2b8;
            color: white;
        }

        #deletelertModal .modal-footer .btn-dark {
            background-color: #343a40;
            color: white;
        }
        .btn-close-custom {
            filter: invert(48%) sepia(81%) 
            saturate(1853%) hue-rotate(157deg);
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
                <h4 class = "mt-2">Active Task List</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12 mb-3 d-flex justify-content-between">
                        <form method="POST" class="d-inline">
                            <input type="text" name="search" class="form-control d-inline border border-dark" style="width: auto;" placeholder="Search Task">
                            <button type="submit" class="btn btn-dark mb-1 text-info">Search</button>
                        </form>
                        <div class = "justify-content-end ">
                            <button type="button" class="btn btn-dark text-info" id = "printBtn">Print <i class="fa fa-print"></i></button>               
                            <button type="button" class="btn btn-dark text-info" id="assignTaskBtn">Assign Task</button>
                        </div>
                    </div>

                    <!-- TABLES START -->
                    <div class="col-12">
                        <table class="table table-bordered table-striped table-light display mr-10" id="taskTable" style="width: 100%">
                            <thead class="thead-dark text-center">
                                <tr class="table-dark" style="font-size: 12px;">
                                    <th class = "text-center">Id</th>
                                    <th class = "text-center">Department</th>
                                    <th class = "text-center">Employee Name</th>
                                    <th class = "text-center">Task Title</th>
                                    <th class = "text-center">Task Description</th>
                                    <th class = "text-center">Start Date</th>
                                    <th class = "text-center">End Date</th>
                                    <th class = "text-center">Days Remaining</th>
                                    <th class = "text-center">Remarks</th>
                                    <th class = "text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr style="font-size: 12px;" data-task-id="<?php echo $row['id']; ?>">
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo $row['department']; ?></td>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['task']; ?></td>
                                            <td><?php echo $row['taskdescription']; ?></td>
                                            <td><?php echo date('M d, Y h:i:s a', strtotime($row['startdate'])); ?></td>
                                            <td><?php echo date('M d, Y h:i:s a', strtotime($row['enddate'])); ?></td>
                                            <td class="days-remaining" 
                                                data-taskid="<?php echo $row['id']; ?>" 
                                                data-startdate="<?php echo $row['startdate']; ?>" 
                                                data-enddate="<?php echo $row['enddate']; ?>">
                                                Loading...
                                            </td>
                                            <td><?php echo $row['remarks']; ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <!-- DROPDOWN PARA SA AKONG MGA ACTIONS -->
                                                    <button class="btn btn-transparent btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid black; border-radius: 15px; color: black;">
                                                        View Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <!-- EDIT TASK BUTTON NI DIRIA-->
                                                        <li>
                                                            <button class="dropdown-item"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#updateTaskModal" 
                                                                    data-taskid="<?php echo $row['id']; ?>"
                                                                    data-task="<?php echo $row['task']; ?>"
                                                                    data-taskdescription="<?php echo $row['taskdescription']; ?>"
                                                                    data-startdate="<?php echo $row['startdate']; ?>" 
                                                                    data-enddate="<?php echo $row['enddate']; ?>" 
                                                                    title="Edit">
                                                                <i class="fa-solid fa-file-pen me-2"></i>Edit
                                                            </button>
                                                        </li>
                                                        <!-- DELETE TASK BUTTON NI D IRIA -->

                                                        <li>
                                                            <a href="#" 
                                                            class="dropdown-item"
                                                            data-id="<?php echo $row['id']; ?>"
                                                            onclick="showdeleteAlert(this);" 
                                                            title="Confirm">
                                                                <i class="fa fa-file-circle-xmark me-2"></i>Delete Task
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <!-- VIEW COMMENTS BUTTON NI DIRIA -->
                                                            <button class="dropdown-item"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#commentsModal<?php echo $row['id']; ?>"
                                                                    title="View Comment">
                                                                <i class="fa-solid fa-comment-dots me-2"></i>View Comment
                                                            </button>
                                                        </li>
                                                    </ul>

                                                    <!-- MODAL PANG READ OG COMMENTS -->
                                                    <div class="modal fade" id="commentsModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="commentsModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-dark text-info">
                                                                    <h5 class="modal-title" id="commentsModalLabel">
                                                                        <i class="fa-solid fa-comment-dots me-2"></i>View Comments for Task "
                                                                        <?php echo $row['task']; ?>"
                                                                    </h5>
                                                                    <button type="button" class="close close-btn btn-close btn-close-custom" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="" method="POST">
                                                                        <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                                                                        <div class="form-group">
                                                                            <textarea class="form-control" 
                                                                                    id="comments" 
                                                                                    name="comments" 
                                                                                    rows="3" 
                                                                                    readonly><?php echo $row['comments']; ?>
                                                                            </textarea>
                                                                        </div>
                                                                    </form>
                                                                </div>
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
                    <!-- TABLE ENDS -->

                    <!-- PAGINATION NI DIRI -->
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
                    <!-- END SA PAGINATIONS -->

                    <!--ASSIGN TASK MODAL NI DIRIA  -->
                    <div class="modal fade" id="assignTaskModal" tabindex="-1" aria-labelledby="assignTaskLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-dark text-info">
                                    <h5 class="modal-title" id="assignTaskLabel">
                                        <i class="bi bi-file-plus me-2"></i>
                                        Assign Task
                                    </h5>
                                    <button type="button" class="close close-btn btn-close btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"></span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="assignTaskForm">
                                        <div class="form-group">
                                            <label for="department">Select Department</label>
                                            <select class="form-control" id="department" name="department" required></select>
                                        </div>
                                        <div class="form-group">
                                            <label for="employee">Select Employee</label>
                                            <select class="form-control" id="employee" name="employee" required></select>
                                        </div>
                                        <div class="form-group">
                                            <label for="tasktitle">Task Title</label>
                                            <input type="text" class="form-control" id="tasktitle" name="tasktitle" placeholder="Task Title" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="taskdescription">Task Description</label>
                                            <textarea class="form-control" id="taskdescription" name="taskdescription" placeholder="Task Description" rows="2" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="startdate">Start Date</label>
                                            <input type="datetime-local" class="form-control" id="startdate" name="startdate" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="enddate">End Date</label>
                                            <input type="datetime-local" class="form-control" id="enddate" name="enddate" required>
                                        </div>
                                        <div id="error-container" class="text-center alert alert-danger container-sm" style="display: none;">
                                            <p id="error-message" style="margin-top: 10px; font-size: 14px;"></p>
                                        </div>
                                        <div class="modal-footer">
                                        <button type="submit" class="btn btn-dark text-info">Assign Task</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END ASSIGN TASK MODAL NI DIRIA -->

                    <!-- UPDATE TASK MODAL NI DIRIA -->
                    <div class="modal fade" id="updateTaskModal" tabindex="-1" role="dialog"aria-labelledby="updateTaskLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-dark text-info">
                                    <h5 class="modal-title" id="assignTaskLabel">
                                        <i class="fa-solid fa-file-pen me-2"></i>
                                         Update Task
                                    </h5>
                                    <button type="button" class="close close-btn btn-close btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"></span>
                                    </button>
                                </div>
                                <form method="POST" id="updateTaskForm">
                                    <div class="modal-body">
                                        <input type="hidden" id="taskId" name="taskId">               
                                        <div class="form-group">
                                            <label for="update-task">Task Title</label>
                                            <input type="text" class="form-control" id="update-task" name="task" placeholder="Task Title" required>
                                        </div> 
                                        <div class="form-group">
                                            <label for="update-taskdescription">Task Description</label>
                                            <input type="text" class="form-control" id="update-taskdescription" name="taskdescription" placeholder="Task Description" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="update-startdate">Start Date</label>
                                            <input type="datetime-local" class="form-control" id="update-startdate" name="startdate" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="update-enddate">End Date</label>
                                            <input type="datetime-local" class="form-control" id="update-enddate" name="enddate" required>
                                        </div>
                                        <div id="error-div" class="text-center alert alert-danger container-sm" style="display: none;">
                                            <p id="error-mess" style="margin-top: 10px; font-size: 14px;"></p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-dark text-info">Update Task</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- END OF UPDATE TASK MODAL NI DIRIA -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
//RESPONSIVE COUNTDOWN TIMER
 document.addEventListener("DOMContentLoaded", function () {
    const countdownElements = document.querySelectorAll(".days-remaining");

    countdownElements.forEach(element => {

        const taskId = element.getAttribute("data-taskid");
        const startDate = new Date(element.getAttribute("data-startdate"));
        const endDate = new Date(element.getAttribute("data-enddate"));

        function updateCountdown() {
            const now = new Date();

            if (now >= endDate) {
                element.innerText = "Did Not Finish";
                fetch("move_task.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: `task_id=${taskId}`
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.error("Error:", error);
                });

                return;
            }
            //PANG DISPLAY OG RESPONSiVE TIME
            const totalDuration = endDate - startDate;
            const timeElapsed = now - startDate;
            const remainingTime = totalDuration - timeElapsed;

            const days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
            const hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

            if(remainingTime <= 3600000) {
                element.classList.add("blink-red");
                element.innerHTML = `<span>${days}d</span>
                                    <span>${hours}h</span>
                                    <span>${minutes}m</span>
                                    <span>${seconds}s</span>`;
            } else {
                element.classList.remove("blink-red");
                element.innerHTML = `<span style="color: green;">${days}d</span>
                                    <span style="color: blue;">${hours}h</span>
                                    <span style="color: black;">${minutes}m</span>
                                    <span style="color: red;">${seconds}s</span>`;
            }
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
    //PANG BLINK2 SA TIME IF ANG TIMER IS LESS THAN 1 HOUR
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
});


//JAVASCRIPT PARA SA ASSIGN TASK MODAL
document.getElementById('assignTaskBtn').addEventListener('click', function () {
    $('#assignTaskModal').modal('show');
});

document.getElementById('assignTaskForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const errormessage = document.getElementById('error-message');
    const errorContainer = document.getElementById('error-container');
    const errortext = "Please fill out all fields!";

    const now = new Date();
    now.setHours(now.getHours() - 1);
    
    if (errortext) {
        errormessage.textContent = errortext;
        errorContainer.style.display = 'block';
    } else {
        errorContainer.style.display = 'none';
    }

    const formData = new FormData(this);
    const startdate = new Date(formData.get('startdate'));
    const enddate = new Date(formData.get('enddate'));

    errormessage.textContent = '';
    errorContainer.style.display = 'none';

    if (startdate > enddate) {
        errormessage.textContent = 'Start date cannot be greater than end date.';
        errormessage.style.color = 'red';

        errorContainer.style.height = '40px'; 
        errorContainer.style.width = '100%';
        errorContainer.style.margin = '10px auto';
        errorContainer.style.display = 'flex'
        errorContainer.style.alignItems = 'center'; 
        errorContainer.style.justifyContent = 'center'; 
        errorContainer.style.textAlign = 'center'; 
        errorContainer.style.border = '1px solid red'; 
        errorContainer.style.backgroundColor = '#f8d7da'; 
        return; 
    }

    if(now > startdate) {

        errormessage.textContent = 'Start date  cannot be less than present date';
        errormessage.style.color = 'red';

        errorContainer.style.height = '40px'; 
        errorContainer.style.width = '100%';
        errorContainer.style.margin = '10px auto';
        errorContainer.style.display = 'flex'
        errorContainer.style.alignItems = 'center'; 
        errorContainer.style.justifyContent = 'center'; 
        errorContainer.style.textAlign = 'center'; 
        errorContainer.style.border = '1px solid red'; 
        errorContainer.style.backgroundColor = '#f8d7da'; 
        return; 
    }
    fetch('assign-task.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        Swal.fire({
            icon: 'success',
            title: 'Task Assigned Successfully',
            showConfirmButton: true,
            confirmButtonText: 'OK',
            timer: 2000
        }).then(() => {
            location.reload();
        });
        $('#assignTaskModal').modal('hide');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to assign task.');
    });
});

//JAVASCRIPT PARA SA UPDATE TASK MODAL
$(document).ready(function() {
    $('#updateTaskModal').on('show.bs.modal', function(event) {
        //  PARA NAAY VALUE ANG MGA INPUT FIELDS SA UPDATE TASK MODAL
        var button = $(event.relatedTarget); 
        var taskId = button.data('taskid'); 
        var task = button.data('task');
        var taskDescription = button.data('taskdescription');
        var startDate = button.data('startdate');
        var endDate = button.data('enddate');

        
        $('#taskId').val(taskId);   
        $('#update-task').val(task);
        $('#update-taskdescription').val(taskDescription);
        $('#update-startdate').val(startDate);
        $('#update-enddate').val(endDate); 
    });
// KARI ANG LOGIC SA PAG UPDATE OG TASK
    $('#updateTaskForm').on('submit', function(e) {
        e.preventDefault()

        const errormessage = document.getElementById('error-mess');
        const errordiv = document.getElementById('error-div');
        const errortext = "Please fill out all fields!";

        const now = new Date(); 
        now.setHours(now.getHours() - 1);
         
        if (errortext) {
            errormessage.textContent = errortext;
            errordiv.style.display = 'block';
        } else {
            errordiv.style.display = 'none';
        }
        
        const formData = new FormData(this);
        const startdate = new Date(formData.get('startdate'));
        const enddate = new Date(formData.get('enddate'));

        errormessage.textContent = '';
        errordiv.style.display = 'none';

        if (startdate > enddate) {

            errormessage.textContent = 'Start date cannot be greater than end date.';
            errormessage.style.color = 'red';

            errorContainer.style.height = '40px'; 
            errorContainer.style.width = '100%';
            errorContainer.style.margin = '10px auto';
            errorContainer.style.display = 'flex'
            errorContainer.style.alignItems = 'center'; 
            errorContainer.style.justifyContent = 'center'; 
            errorContainer.style.textAlign = 'center'; 
            errorContainer.style.border = '1px solid red'; 
            errorContainer.style.backgroundColor = '#f8d7da'; 
            return; 
        }
        $.ajax({
            url: 'update-task.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Task Updated Successfully',
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    timer: 2000
                }).then(() => {
                    location.reload();
                });
                $('#updateTaskModal').modal('hide');
            },

            error: function() {
                alert('An error occurred. Please try again.');
                $('#updateTaskModal').modal('hide');
                location.reload();          
            }
        });
    });
});

//CUSTOM ALERT SA DELETE TASK
function showdeleteAlert(button) {
     
     const taskId = button.getAttribute('data-id');
     const submitLink = `delete-task.php?id=${taskId}`;

     
     Swal.fire({
        title: 'Confirm Task Deletion',
        text: 'Are you sure you want to delete this task?',
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
    });;
 }

 //JAVASCRIPT SA PAG PRINT TASK
 document.getElementById('printBtn').addEventListener('click', function () {
    const table = document.getElementById('taskTable').cloneNode(true);
    const rows = table.querySelectorAll('tr');


    rows.forEach(row => {
        if (row.children.length > 7) {
            row.removeChild(row.children[7]); 
            row.removeChild(row.children[8]); 
        }
    });

    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Print Task List</title>
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                <style>
                    body {
                        padding: 20px;
                    }
                    .card {
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                    }
                    h4 {
                        margin-top: 10px;
                    }
                </style>
            </head>
            <body>
                <div class="container mt-3">
                    <div class="card shadow-lg">
                        <div class="card-header bg-dark text-info">
                            <h4 class="mt-2">Active Task List</h4>
                        </div>
                        <div class="card-body">
                            ${table.outerHTML}
                        </div>
                    </div>
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
});

//JAVASCRIPT SA DATATABLES
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
</script>
</body>
</html>  