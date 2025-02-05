<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();

if (!isset($_SESSION['admin-login'])) {
    header('Location: index.php');
    exit();
}

$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$recordsPerPage = 8;
$offset = ($currentPage - 1) * $recordsPerPage;

$sql = "SELECT * FROM department_tbl 
        WHERE department LIKE '%$searchTerm%' 
        LIMIT $offset, $recordsPerPage";
$result = $conn->query($sql);

$sqlCount = "SELECT COUNT(*) AS total 
             FROM department_tbl 
             WHERE department LIKE '%$searchTerm%'";
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
    <title>Department List</title>
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
    <style>
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
</head>
<body>
<?php include('/xampp/htdocs/task/includes/navbar.php'); ?>

<div class="container mt-3 min-vh-100">
    <div class="card shadow-lg">
        <div class="card-header bg-dark text-info">
            <h4 class = "mt-2">Department List</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-12 d-flex justify-content-between">
                    <form method="POST" class="d-inline">
                        <input type="text" name="search" class="form-control d-inline border border-dark" style="width: auto;" placeholder="Search Department">
                        <button type="submit" class="btn btn-dark text-info mb-1">Search</button>
                    </form>
                    <button class="btn btn-dark text-info mb-1" data-toggle="modal" data-target="#addDepartmentModal">Add Department</button>
                </div>
            </div>
            <table class="table table-bordered  table-light table-striped display" id = "deptTable">
                <thead class="table-dark">
                    <tr style = "font-size: 12px">
                        <th class = "text-center">Department Id</th>
                        <th class = "text-center">Department</th>
                        <th class = "text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr style = "font-size: 12px">
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['department']; ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-info btn-sm me-2" data-toggle="modal" data-target="#updateDepartmentModal" 
                                                data-id="<?php echo $row['id']; ?>"
                                                data-department="<?php echo $row['department']; ?>" title="Edit">
                                            <i class="fa-solid fa-sitemap"></i>
                                        </button>
                                        <button href="#" 
                                            class="btn btn-warning btn-sm ml-2"
                                            data-id="<?php echo $row['id']; ?>"
                                            onclick="showdeleteAlert(this);" 
                                            title="Confirm">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                    <?php endif; ?>
                </tbody>
            </table>

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

<!-- ADD DEPARTMENT MODAL -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-info">
                <h5 class="modal-title" id="addDepartmentModalLabel">
                    <i class="fa-solid fa-sitemap me-2"></i>Add Department</h5>
                <button type="button" class="close btn-close btn-close-custom" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addDepartmentForm">
                    <div class="form-group">
                        <label for="department">Department Name</label>
                        <input type="text" class="form-control" name="department" id="department" placeholder="Enter Department Name" required>
                    </div>
                    <div class = "modal-footer"> 
                        <button type="submit" class="btn btn-dark text-info">Add Department</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- UPDATE EMPLOYEE MODAL -->
<div class="modal fade" id="updateDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="updateDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-info">
                <h5 class="modal-title" id="updateDepartmentModalLabel">
                    <i class="fa-solid fa-sitemap"></i>
                    Update Department
                </h5>
                <button type="button" class="close btn-close btn-close-custom" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateDepartmentForm">
                    <input type="hidden" id="updateId" name="id">
                    <div class="form-group">
                        <label for="updateDepartment">Department Name</label>
                        <input type="text" class="form-control" name="department" id="updateDepartment" placeholder="Enter Department Name" required>
                    </div>
                    <div class = "modal-footer">
                        <button type="submit" class="btn btn-dark btn-block text-info ml-5">Update Department</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
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
                Department added successfully!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="customUpdateAlertModal" tabindex="-1" aria-labelledby="customUpdateAlertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="customUpdateAlertModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Department updated successfully!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- DELETE DEPAERTMENT CUSTOM ALERT -->
<div class="modal fade" id="deleteAlertModal" tabindex="-1" aria-labelledby="deleteAlertModaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAlertModaLabel">Delete Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to Delete this Department?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="deleteSubmitBtn" class="btn btn-info">Confirm</a>
            </div>
        </div>
    </div>
</div>

<!-- DELETE DEPARTMENT CUSTOM ALERT ENDS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('#updateDepartmentModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var departmentId = button.data('id');
        var departmentName = button.data('department');

        $('#updateId').val(departmentId);
        $('#updateDepartment').val(departmentName);
        });
    $('#addDepartmentForm').submit(function(e) {
    e.preventDefault();  
    $.ajax({
        url: 'add-department.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                $('#addDepartmentModal').modal('hide');
                $('#customAlertModal').modal('show');
                setTimeout(() => location.reload(), 1000);
            } else {
                alert(response.message);
            }
        },
        error: function () {
            alert('An error occurred while adding the department. Please try again.');
        }
    });
});

$('#updateDepartmentForm').submit(function(e) {
    e.preventDefault();

    $.ajax({
        url: 'update-department.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                $('#updateDepartmentModal').modal('hide');
                $('#customUpdateAlertModal').modal('show');
                setTimeout(() => location.reload(), 1000);
            } else {
                alert(response.message);
            }
        },
        error: function () {
            alert('An error occurred while updating the department. Please try again.');
            }
        });
    });
});
    
    function showdeleteAlert(button) {
     
     const taskId = button.getAttribute('data-id');
     const submitLink = `delete-department.php?id=${taskId}`;

     
     document.getElementById('deleteSubmitBtn').setAttribute('href', submitLink);

     
     const deleteAlertModal = new bootstrap.Modal(document.getElementById('deleteAlertModal'));
     deleteAlertModal.show();
 }
    $(document).ready(function () {
        $('#deptTable').DataTable({
            paging: false,        
            searching: false,     
            ordering: true,       
            info: false,          
            lengthChange: false,  
            columnDefs: [
                { targets: 2, orderable: false }
            ]
        });
    });
</script>

</body>
</html>
