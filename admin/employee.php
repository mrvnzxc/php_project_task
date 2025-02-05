<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();
//FOR LOGIN SESSION CHECKING
if (!isset($_SESSION['admin-login'])) {
    header('Location: index.php');
    exit();
}
//FOR SEARCH PURPOSES
$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$recordsPerPage = 10;  
$offset = ($currentPage - 1) * $recordsPerPage;

$sql = "SELECT employee_tbl.*, department_tbl.department AS department 
        FROM employee_tbl
        LEFT JOIN department_tbl ON employee_tbl.department = department_tbl.id 
        WHERE employee_tbl.fname LIKE '%$searchTerm%' 
        OR employee_tbl.lname LIKE '%$searchTerm%' 
        OR department_tbl.department LIKE '%$searchTerm%' 
        OR employee_tbl.username LIKE '%$searchTerm%' 
        LIMIT $offset, $recordsPerPage";

$result = $conn->query($sql);

$sqlCount = "SELECT COUNT(*) AS total FROM employee_tbl
             LEFT JOIN department_tbl ON employee_tbl.department = department_tbl.id 
             WHERE employee_tbl.fname LIKE '%$searchTerm%' 
             OR employee_tbl.lname LIKE '%$searchTerm%' 
             OR department_tbl.department LIKE '%$searchTerm%' 
             OR employee_tbl.username LIKE '%$searchTerm%'";

$countResult = $conn->query($sqlCount);
$rowCount = $countResult->fetch_assoc();
$totalRecords = $rowCount['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

$departmentCount = $conn->query("SELECT * FROM department_tbl");
$departments = $departmentCount->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="js/fetchdepartment.js"></script>
    <script src="js/fetchemployee.js"></script>
    <!-- DATATABLES CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

</head>
<style>
    .password-toggle {
        position: absolute;
        transform: translateY(-50%);
        background: none;
        margin-left: 430px;
        margin-top: -18px;
        border: none;
        cursor: pointer;
        outline: none;
        font-size: 1rem;
    }
    .password-wrapper {
        position: relative;
    }
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
        .btn-close-custom {
            filter: invert(48%) sepia(81%) 
            saturate(1853%) hue-rotate(157deg);
        }
        #deleteAlertModal .modal-header {
            background-color: #17a2b8; 
            color: white;
        }
        #deleteAlertModal .modal-footer .btn-dark {
            background-color: #343a40; 
            color: white;
        }
    </style>
</style>
<body>
<?php include('/xampp/htdocs/task/includes/navbar.php'); ?>
<div class="container mt-3 min-vh-100">
    <div class="card shadow-lg">
        <div class="card-header bg-dark text-info">
            <h4 class = "mt-2">Employees List</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-12 d-flex justify-content-between">
                    <form method="POST" class="d-inline">
                        <input type="text" name="search" class="form-control d-inline border border-dark" style="width: auto;" placeholder="Search Employee">
                        <button type="submit" class="btn btn-dark text-info mb-1">Search</button>
                    </form>
                    <button class="btn btn-dark text-info mb-1" data-toggle="modal" data-target="#addEmployeModal">Add Employee</button>
                </div>
            </div>
            <table class="table table-bordered  table-light table-striped display" id = "employeeTable">
                <thead class="table-dark">
                    <tr style = "font-size: 12px">
                        <th class = "text-center">Employee Id</th>
                        <th class = "text-center">First Name</th>
                        <th class = "text-center">Last Name</th>
                        <th class = "text-center">Department</th>
                        <th class = "text-center">Username</th>
                        <th class = "text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr style = "font-size: 12px">
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['fname']; ?></td>
                                <td><?php echo $row['lname']; ?></td>
                                <td><?php echo $row['department']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td class = "text-center">
                                    <div class="btn-group">
                                    <button class="btn btn-info btn-sm me-2" data-toggle="modal" data-target="#updateEmployeeModal" 
                                                data-id="<?php echo $row['id']; ?>"
                                                data-fname = "<?php echo $row['fname']; ?>"
                                                data-lname = "<?php echo $row['lname']; ?>"
                                                data-employee="<?php echo $row['department']; ?>" 
                                                data-username="<?php echo $row['username']; ?>" 
                                                title="Edit">
                                            <i class="fa-solid fa-user-pen"></i>
                                        </button>
                                        <button href="#" 
                                            class="btn btn-warning btn-sm ml-2"
                                            data-id="<?php echo $row['id']; ?>"
                                            onclick="showdeleteAlert(this);" 
                                            title="Confirm">
                                            <i class="fa-solid fa-user-xmark"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <!-- PAGINATION STARTS -->
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
            <!-- PAGINATION ENDS -->
        </div>
    </div>
</div>

<!-- MODAL FOR ADDING EMPLOYEES -->
<div class="modal fade" id="addEmployeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-info">
                <h5 class="modal-title" id="addEmployeeLabel">
                    <i class="fa-solid fa-user-plus me-2"></i>Add Employee</h5>
                <button type="button" class="close btn-close btn-close-custom" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form id="addEmployeeForm" action="add-employee.php" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter First Name" required>
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter Last Name" required>
                    </div>
                    <div class="form-group">
                        <label for="department">Select Department</label>
                        <select class="form-control" id="department" name="department" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?php echo $department['id']; ?>"><?php echo $department['department']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
                    </div>
                    <div id="error-container" class="text-center alert alert-danger container-sm mt-2" style="display: none; padding: 5px 15px; height: 40px; max-height: 40px;">
                        <p id="error-message" style="margin: 0; font-size: 12px; word-wrap: break-word; margin-top: 5px"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-dark text-info">Add Employee</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END DIV FOR ADDING MODAL -->

<!-- MODAL FOR UPDATING EMPLOYEES -->
<div class="modal fade" id="updateEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="updateEmployeeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-info">
                <h5 class="modal-title" id="updateEmployeeLabel">
                    <i class="fa-solid fa-user-pen me-2"></i>
                    Update Employee
                </h5>
                <button type="button" class="close btn-close btn-close-custom" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form method="POST" id="updateEmployeeForm" action="update-employee.php">
                <div class="modal-body">
                <input type="hidden" id="id" name="id">               
                    <div class="form-group">
                        <label for="update-fname">First Name</label>
                        <input type="text" class="form-control" id="update-fname" name="fname" placeholder="Enter first name" required>
                    </div>
                    <div class="form-group">
                        <label for="update-lname">Last Name</label>
                        <input type="text" class="form-control" id="update-lname" name="lname" placeholder="Enter last name" required>
                    </div>
                    <div class="form-group">
                        <label for="department">Select Department</label>
                        <select class="form-control" id="department" name="department" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?php echo $department['id']; ?>"><?php echo $department['department']; ?></option>
                            <?php endforeach; ?>  
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="update-username">Username</label>
                        <input type="text" class="form-control" id="update-username" name="username" placeholder="Enter username" required>
                    </div>
                    <div id="error-update" class="text-center alert alert-danger container-sm" style="display: none; padding: 5px 15px; height: 40px; max-height: 40px;">
                        <p id="error-updatemessage" style="margin-top: 5px; font-size: 12px; word-wrap: break-word;"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-dark text-info">Update Employee</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="customAlertModal" tabindex="-1" aria-labelledby="customAlertLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="customAlertLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Employee added successfully!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="customUpdateAlertModal" tabindex="-1" aria-labelledby="customUpdateAlertLabel" aria-hidden="true">
    <div class="modal-dialog"> 
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="customUpdateAlertLabel">Employee Updated</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Employee updated successfully!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END DIV OF MODAL FOR UPDATING EMPLOYEES -->

<div class="modal fade" id="deleteAlertModal" tabindex="-1" aria-labelledby="deleteAlertModalAlertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAlertModalAlertModalLabel">Delete Employee</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to Delete this Employee?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
                <a href="#" id="deleteSubmitBtn" class="btn btn-info">Confirm</a>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    // FOR FETCHING DEPARTMENT IN UPDATING EMPLOYEES MODAL
    $(document).ready(function() {
    fetchDepartments();
    $('#updateEmployeeModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget); 
    var id = button.data('id'); 
    var fname = button.data('fname');
    var lname= button.data('lname');
    var department = button.data('department');
    var username = button.data('username')

    $('#id').val(id);   
    $('#update-fname').val(fname);
    $('#update-lname').val(lname);
    $('#update-department').val(department);
    $('#update-username').val(username); 

});
    function fetchDepartments() {
        $.ajax({
            url: 'fetch-department.php',
            method: 'GET',
            success: function(response) {
                $('#department').html(response);
            }
        });
    }
    // RESPONSES FOR ADDING EMPLOYEES RESULTS
    $('#addEmployeeForm').on('submit', function(e) {
    e.preventDefault();               
    $.ajax({
        url: 'add-employee.php',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json', 
        success: function(response) {
            if (response.status === 'error') {
                $('#error-container').show();
                $('#error-message').text(response.message);
            } else if (response.status === 'success') {
                $('#customAlertModal').modal('show');
                $('#addEmployeModal').modal('hide');
                setTimeout(() => location.reload(), 2000);
            }
        },
        error: function() {

            $('#error-updatemessage').text('An error occurred while adding the employee.');
            $('#error-update').show();
        }
    });
});
// RESPONSES FOR UPDATING EMPLOYEES
$('#updateEmployeeForm').on('submit', function(e) {
    e.preventDefault();

    $('#error-update').hide(); 

    $.ajax({
        url: 'update-employee.php',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.error) {
                $('#error-updatemessage').text(response.error);
                $('#error-update').show();
            } else {
                $('#customUpdateAlertModal').modal('show'); 
                $('#updateEmployeeModal').modal('hide');
                setTimeout(() => location.reload(), 2000);
            }
        },
        error: function() {

            $('#error-updatemessage').text('An error occurred while updating the employee.');
            $('#error-update').show();
        }
    });
});
});
function showdeleteAlert(button) {
     
     const taskId = button.getAttribute('data-id');
     const submitLink = `delete-employee.php?id=${taskId}`;

     
     document.getElementById('deleteSubmitBtn').setAttribute('href', submitLink);

     
     const deleteAlertModal = new bootstrap.Modal(document.getElementById('deleteAlertModal'));
     deleteAlertModal.show();
}
$(document).ready(function () {
        $('#employeeTable').DataTable({
            paging: false,        
            searching: false,     
            ordering: true,       
            info: false,          
            lengthChange: false,  
            columnDefs: [
                { targets: 5, orderable: false }
            ]
        });
    });
</script>
</body>
</html>
