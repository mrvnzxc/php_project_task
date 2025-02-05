<?php
include_once('database/dbconnect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => 'Invalid request.'];

    if (isset($_POST['username']) && isset($_POST['pass'])) {
        $username = $_POST['username'];
        $pass = $_POST['pass'];

        $sql = "SELECT * FROM employee_tbl";
        $query = $conn->query($sql) or die(json_encode(['success' => false, 'message' => $conn->error]));
        $employeelogin = false;

        while ($row = $query->fetch_assoc()) {
            $usernameFromDB = $row['username'];
            $passwordFromDB = $row['pass'];

            if ($username == $usernameFromDB && $pass == $passwordFromDB) {
                $employeelogin = true;

                $_SESSION['employee-login'] = true;
                $_SESSION['employee-id'] = $row['id'];
                $_SESSION['fname'] = $row['fname'];
                $_SESSION['lname'] = $row['lname'];

                $response = [
                    'success' => true,
                    'message' => 'Login successful.'
                ];
                break;
            }
        }

        if (!$employeelogin) {
            $response['message'] = 'Invalid username or password.';
        }
        
    }
    echo json_encode($response);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-image: url('5.webp');
            background-size: cover;
            background-repeat: no-repeat;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
        }
        .header h1 {
            margin: 0;
        }
        .login-btn {
            background: #007bff;
            border: none;
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-btn:hover {
            background: #0056b3;
        }
        .submit-btn {
            margin-bottom: 2px;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background: #0056b3;
        }
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

        .modal-content {
            background: #f9f9f9;
        }
        .modal-content{
            max-width: 400px;
        }
        .btn-close-custom {
            filter: invert(48%) sepia(81%) 
            saturate(1853%) hue-rotate(157deg);
        }
    </style>
</head>
<body>
    <div class="header bg-dark">
        <h1 class = "text-info">Welcome to Employee Portal</h1>
        <div class = "d-inline ">
            <button class = "submit-btn btn btn-dark btn-outline-info" data-toggle="modal" data-target="#submitbyModal">Group 6</button>
            <button class="login-btn" data-toggle="modal" data-target="#loginModal">Login</button>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-info">
                    <h5 class="modal-title" id="loginModalLabel">Employee Login</h5>
                    <button type="button" class="close text-white btn-close btn-close-custom" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="login.php" method="POST">
                        <div class="form-group">
                            <label for="username">Enter Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
                        </div>
                        <div class="form-group password-wrapper">
                            <label for="pass">Enter Password</label>
                            <input type="password" class="form-control" id="pass" name="pass" placeholder="Enter Password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fa-regular fa-eye-slash" id="toggle-icon"></i>
                            </button>
                        </div>
                        <div class="error-message text-danger text-center mt-2" style="display: none;"></div>
                        <button type="submit" class="btn btn-dark w-100 text-info mt-3">
                            <i class="fa-solid fa-right-to-bracket"></i> Login
                        </button>
                    </form>
                    <div class="text-center mt-2">
                        <a href="admin/index.php?modal=open" class="admin text-decoration-none text-dark">Login as Admin</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class = "modal fade" id = "submitbyModal" tabindex="-1" aria-labelledby="submitbyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-info">
                    <h5 class="modal-title text-center" id="submitbyModalLabel">Task Management System</h5>
                    <button type="button" class="close text-white btn-close btn-close-custom" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <p class = "text-dark">Submitted by:</p>
                    </div>  
                    <div class="text-center">
                        <img src="2.jpg" alt="">
                        <p class = "text-dark">John Marvin Bautista<br><span class = "text-danger">Programmer/Team Leader</span>
                    </p>
                    </div>
                    <div class="text-center">
                        <img src="2.jpg" alt="">
                        <p class = "text-dark">Ethan John Salavedra<br>
                    <span class = "text-danger">Concept/Idea</span></p>
                    </div>
                    <div class="text-center">
                        <img src="2.jpg" alt="">
                        <p class = "text-dark">Jim Michael Sanduka <br>
                    <span class = "text-danger">Support</span></p>
                    </div>
                    <div class="text-center">
                        <img src="2.jpg" alt="">
                        <p class = "text-dark">Val Jeason Behona <br>
                        <span class = "text-danger">Support</span></p>
                    </div>
                    <div class="text-center">
                        <p class = "text-dark">December, 2024</p>
                    </div>
                    <div class="text-center">
                        <p><br></p>
                        <p class = "text-dark">Submitted to:</p>
                    </div>
                    <div class="text-center">
                        <img src="2.jpg" alt="">
                        <p class = "text-dark">Sir. Gremar Campilan <br>
                    Teacher</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function togglePassword() {
            const passInput = document.getElementById('pass');
            passInput.type = passInput.type === 'password' ? 'text' : 'password';
            const toggleIcon = document.getElementById('toggle-icon');
            const isPassword = passInput.type === 'password';
            toggleIcon.className = isPassword 
                ? 'fa-regular fa-eye-slash' 
                : 'fa-regular fa-eye';
        }

        $(document).ready(function () {
            $('form').on('submit', function (e) {
                e.preventDefault();

                const formData = $(this).serialize();

                $.ajax({
                    url: 'login.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            window.location.href = 'index.php';
                        } else {
                            $('.error-message').text(response.message).show();
                        }
                    },
                    error: function () {
                        $('.error-message').text('An unexpected error occurred. Please try again.').show();
                    }
                });
            });
        });
    </script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

