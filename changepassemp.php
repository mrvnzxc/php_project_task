<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();

if (!isset($_SESSION['employee-login'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];

    if (isset($_POST['current_pass'], $_POST['new_pass'], $_POST['confirm_pass'])) {
        $current_pass = $_POST['current_pass'];
        $new_pass = $_POST['new_pass'];
        $confirm_pass = $_POST['confirm_pass'];

        $employeeId = $_SESSION['employee-id'];
        $sql = "SELECT pass FROM employee_tbl WHERE id = '$employeeId' LIMIT 1";
        $query = $conn->query($sql);

        if ($query && $row = $query->fetch_assoc()) {
            $passwordFromDB = $row['pass'];

            if ($current_pass === $passwordFromDB) {
                if ($new_pass === $confirm_pass) {
                    $sqlUpdate = "UPDATE employee_tbl SET pass='$new_pass' WHERE id = '$employeeId' LIMIT 1";

                    if ($conn->query($sqlUpdate)) {
                        $response['success'] = true;
                        $response['message'] = 'Password changed successfully!';
                    } else {
                        $response['message'] = 'Error updating password: ' . $conn->error;
                    }
                } else {
                    $response['message'] = 'New password and confirm do not match.';
                }
            } else {
                $response['message'] = 'Current password is incorrect.';
            }
        } else {
            $response['message'] = 'Error fetching current password.';
        }
    } else {
        $response['message'] = 'All fields are required.';
    }

    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 73%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            outline: none;
            font-size: 1rem;
        }

        .password-wrapper {
            position: relative;
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
    </style>
</head>
<body>
<?php include('employeenavbar.php'); ?>
<div class="container mt-5 min-vh-100">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg">
                <div class="card-header bg-dark text-info">
                    <h4>
                    <i class="bi bi-key-fill me-2"></i>Change Password
                    </h4>
                </div>
                <div class="card-body">
                    <form id="changePasswordForm" action="changepassemp.php" method="POST">
                        <div class="form-group password-wrapper">
                            <label for="current_pass">Current Password</label>
                            <input type="password" class="form-control" id="current_pass" name="current_pass" placeholder="Current Password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('current_pass', 'toggle-icon-current')">
                                <i class="fa-regular fa-eye-slash" id="toggle-icon-current"></i>
                            </button>
                        </div>
                        <div class="form-group password-wrapper">
                            <label for="new_pass">New Password</label>
                            <input type="password" class="form-control" id="new_pass" name="new_pass" placeholder="New Password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('new_pass', 'toggle-icon-new')">
                                <i class="fa-regular fa-eye-slash" id="toggle-icon-new"></i>
                            </button>
                        </div>
                        <div class="form-group password-wrapper">
                            <label for="confirm_pass">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_pass" name="confirm_pass" placeholder="Confirm New Password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('confirm_pass', 'toggle-icon-confirm')">
                                <i class="fa-regular fa-eye-slash" id="toggle-icon-confirm"></i>
                            </button>
                        </div>  
                        <div id="responseMessage" class="errorContainer"></div>    
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-dark text-info">Change Password</button>
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 
</body>
<script>
    document.getElementById('changePasswordForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const responseMessage = document.getElementById('responseMessage');

        try {
            const response = await fetch('changepassemp.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            responseMessage.textContent = result.message;
            responseMessage.style.display = 'flex';
            responseMessage.className = result.success ? 'successContainer' : 'errorContainer';

            if (result.success) {
                setTimeout(() => window.location.href = 'index.php', 2000);
            }
        } catch (error) {
            responseMessage.textContent = 'An error occurred while processing your request.';
            responseMessage.style.display = 'flex';
            responseMessage.className = 'errorContainer';
        }
    });

    function togglePassword(inputId, iconId) {
        const passInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);

        const isPassword = passInput.type === "password";
        passInput.type = isPassword ? "text" : "password";
        toggleIcon.className = isPassword 
            ? "fa-regular fa-eye"
            : "fa-regular fa-eye-slash";
    }
</script>
</html>