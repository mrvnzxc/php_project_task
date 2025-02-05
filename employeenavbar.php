<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SideBar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<style>
    body {
    display: flex;
    height: 125vh;
    margin: 0;
    font-family: Arial, sans-serif;
}
.sidebar {
    background-color: #343a40;
    color: white;
    height: 100%;
    transition: width 0.3s;
    padding-top: 20px;
    position: relative;
    width: 250px;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.sidebar.collapsed {
    width: 60px;
    align-items: center;
}
.header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    cursor: pointer;
    width: 100%;
}
.sidebar.collapsed .header {
    flex-direction: column;
    gap: 15px;
}
.hamburger {
    width: 30px;
    height: 30px;
    cursor: pointer;
}
.hamburger span {
    display: block;
    width: 100%;
    height: 3px;
    background-color: #17a2b8;
    margin: 6px 0;
    transition: 0.3s;
}
.admin-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 2px solid #17a2b8;
    transition: opacity 0.3s, margin 0.3s;

}
.navbar-brand {
    font-size: 1.2em;
    color: #17a2b8;
    text-align: center;
    margin-top: 10px;
    margin-left: 6px;
    transition: font-size 0.3s, opacity 0.3s;
}
.sidebar.collapsed .navbar-brand {
    display: none;
}
.navbar-nav a {
    color: white;
    text-decoration: none;
    padding: 12px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    width: 100%;
    margin: 5px 0;
}
.navbar-nav a i {
    margin-right: 5px;
}
.sidebar.collapsed .navbar-nav a span {
    display: none;
}
.dropdown {
    position: absolute;
    top: 60px;
    left: 20px;
    background-color: #343a40;
    color: white;
    border: 1px solid #17a2b8;
    border-radius: 5px;
    padding: 10px;
    display: none;
    width: 200px;
}
.dropdown p {
    font-weight: bold;
    margin: 0 0 10px 0;
}
.dropdown a {
    color: #17a2b8;
    text-decoration: none;
    display: block;
    margin: 5px 0;
}
.dropdown a:hover {
    text-decoration: underline;
}
</style>
<div class="sidebar bg-dark" id="sidebar">
    <div class="header" ">
        <div class="hamburger" onclick="toggleSidebar(event)">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <i class="fa-solid fa-user-ninja text-info" alt="Admin" class="admin-avatar" id="adminAvatar" onclick="toggleDropdown(event)"></i>
    </div>
    <a class="navbar-brand" href="index.php">Employee Dashboard</a>
    <div class="dropdown" id="adminDropdown">
        <p class="text-info">
        <?php echo $_SESSION['fname'];?>
        <?php echo $_SESSION['lname'];?>
        </p>
        <a href="changepassemp.php">Change Password</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="navbar-nav">
        <a class="nav-link active text-info" href="index.php">
            <i class="fa-regular fa-folder-open"></i> <span>View Task</span>
        </a>
        <a class="nav-link active text-info" href="completed-task.php">
            <i class="fa-solid fa-list-ul"></i> <span>Completed Task</span>
        </a>
        <a class="nav-link active text-info" href="submit.php">
            <i class="fa-solid fa-list-check"></i> <span>Submitted Task</span>
        </a>
    </div>
</div>

<script>
    function toggleSidebar(event) {
        event.stopPropagation(); 
        const sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("collapsed");
    }

    function toggleDropdown(event) {
        event.stopPropagation();
        const dropdown = document.getElementById("adminDropdown");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    window.onclick = function(event) {
        if (!event.target.closest('.admin-avatar')) {
            const dropdown = document.getElementById("adminDropdown");
            if (dropdown.style.display === "block") {
                dropdown.style.display = "none";
            }
        }
    }
</script>

</body>
</html>
