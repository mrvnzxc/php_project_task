<?php
include_once('/xampp/htdocs/task/database/dbconnect.php');
session_start();

if (!isset($_SESSION['admin-login'])) {
    header('Location: index.php');
    exit();
}
$active = "SELECT COUNT(*) AS count FROM task_tbl";
$confirm = "SELECT COUNT(*) AS count FROM confirm_task_tbl";
$finish = "SELECT COUNT(*) AS count FROM finish_task_tbl";

$completed = "SELECT COUNT(*) AS count FROM confirm_task_tbl WHERE remarks = 'Completed'";
$didNotFinish = "SELECT COUNT(*) AS count FROM confirm_task_tbl WHERE remarks = 'Did Not Finish'";
$abandoned = "SELECT COUNT(*) AS count FROM confirm_task_tbl WHERE remarks = 'abandoned'";

$activeTasksResult = $conn->query($active)->fetch_assoc()['count'];
$completedTasksResult = $conn->query($confirm)->fetch_assoc()['count'];
$submittedTasksResult = $conn->query($finish)->fetch_assoc()['count'];

$completedResult = $conn->query($completed)->fetch_assoc()['count'];
$didNotFinishResult = $conn->query($didNotFinish)->fetch_assoc()['count'];
$abandonedTasksResult = $conn->query($abandoned)->fetch_assoc()['count'];

$month = isset($_GET['month']) ? $_GET['month'] : '';

$remarksQuery = "SELECT remarks, COUNT(*) AS count FROM confirm_task_tbl WHERE 1";


if (!empty($month)) {
    $year = substr($month, 0, 4);
    $month = substr($month, 5, 2);  
    $remarksQuery .= " AND MONTH(submitdate) = '$month' AND YEAR(submitdate) = '$year'";
}

$remarksQuery .= " GROUP BY remarks";

$remarksResult = $conn->query($remarksQuery);

$remarksData = [];
while ($row = $remarksResult->fetch_assoc()) {
    $remarksData[] = [$row['remarks'], (int)$row['count']];
}

$remarksDataJson = json_encode($remarksData);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body{
            min-height: 100vh;
            margin: 0;
        }
        .cards {
            transition: transform 0.3s;
            position: relative;
            transition: transform 0.2s ease;
        }
        .cards:hover {
            transform: scale(1.05);
        }
        .card-container {
            margin-bottom: 50px;
        }
        .task-count {
            position: absolute;
            justify-content: end;
            bottom: 15px;
            right: 15px;
            font-size: 1.7em;
            font-weight: bold;
            color: #333;
            text-shadow: 2px 2px #333;
        }
        .card-link {
            text-decoration: none;
            color: inherit;
        }
        .pies{
            background-color: #343a40;
            padding: 2px;
        }
        .bars{
            background-color: #343a40;
            padding: 2px;
        }
        .haha{
            height: auto;
        }
        .container{
            margin-left: 5px;
        }
    </style>
</head>
<body>
<?php include('/xampp/htdocs/task/includes/navbar.php'); ?>
<div class="container mt-2">
        <div class="card shadow-lg bg-info haha">
            <div class="card-header bg-dark text-info">
                <h4 class = "mt-2">Dashboard</h4>
            </div>
            <div class="card-body">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <a href="current-task.php" class="card-link">
                                    <div class="cards border-primary bg-primary h-100">
                                        <div class="card-body d-flex flex-column">
                                            <h3 class="card-title text-light" style = "text-shadow: 2px 2px #333">Active Tasks</h3>
                                            <p class="card-text text-light" style = "text-shadow: 2px 2px #333">Currently Active Tasks</p>
                                            <div class="task-count text-light"><?php echo $activeTasksResult; ?></div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-12 col-md-4">
                                <a href="submitted-task.php" class="card-link">
                                    <div class="cards border-danger bg-danger h-100">
                                        <div class="card-body d-flex flex-column">
                                            <h3 class="card-title text-light" style = "text-shadow: 2px 2px #333">Submitted Tasks</h3>
                                            <p class="card-text text-light" style = "text-shadow: 2px 2px #333">Tasks submitted by employees.</p>
                                            <div class="task-count text-light"><?php echo $submittedTasksResult; ?></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-12 col-md-4">
                                <a href="finish-task.php" class="card-link">
                                    <div class="cards border-success bg-success h-100">
                                        <div class="card-body d-flex flex-column">
                                            <h3 class="card-title text-light" style = "text-shadow: 2px 2px #333">Finished Tasks</h3>
                                            <p class="card-text text-light" style = "text-shadow: 2px 2px #333">Tasks confirmed as finished.</p>
                                            <div class="task-count text-light"><?php echo $completedTasksResult; ?></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12 col-md-4">
                                <div class="cards border-primary bg-primary h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h3 class="card-title text-light"  style = "text-shadow: 2px 2px #333">Completed Tasks</h3>
                                        <p class="card-text text-light" style = "text-shadow: 2px 2px #333">Tasks Completed by employees.</p>
                                        <div class="task-count text-light"><?php echo $completedResult; ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="cards border-danger bg-danger h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h3 class="card-title text-light" style = "text-shadow: 2px 2px #333">Abandoned Tasks</h3>
                                        <p class="card-text text-light" style = "text-shadow: 2px 2px #333">Tasks Abandoned by employees</p>
                                        <div class="task-count text-light"><?php echo $abandonedTasksResult; ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="cards border-success bg-success h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h3 class="card-title text-light" style = "text-shadow: 2px 2px #333">Did Not Finish Tasks</h3>
                                        <p class="card-text text-light" style = "text-shadow: 2px 2px #333">Tasks Did Not Finish by Employees</p>
                                        <div class="task-count text-light"><?php echo $didNotFinishResult; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <!-- Pie Chart -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="months" class="form-label">Filter by Month:</label>
                                    <input type="month" id="months" value="" class="form-control">
                                </div>
                                <div class="cards pie shadow-lg">
                                    <div class="pies">
                                        <div id="piechart" style="width: 100%; height: 303px;"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- BAR CHART -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="month_filter" class="form-label">Filter by Month:</label>
                                    <input type="month" id="month_filter" value="" class="form-control">
                                </div>

                                <div class="cards bar shadow-lg">
                                    <div class = "bars">
                                        <div id="chart_div" style="width: 100%; height: 303px;"></div>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <button id="prev_page" class="btn btn-primary me-2">Previous</button>
                                    <button id="next_page" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawPieChart);

    document.getElementById('months').addEventListener('change', function() {
        const selectedMonth = this.value;
        window.location.href = `dashboard.php?month=${selectedMonth}`; 
    });

    function drawPieChart() {

        const remarksOrder = ['Completed', 'Did Not Finish', 'Abandoned'];


        const remarksData = <?php echo $remarksDataJson; ?>; 
        const orderedData = remarksOrder.map(remark => {
            const entry = remarksData.find(([r]) => r === remark);
            return entry || [remark, 0]; 
        });

        const data = google.visualization.arrayToDataTable([
            ['Remarks', 'Count'],
            ...orderedData
        ]);

        const options = {
            title: 'Completed Task Remarks Distribution',
            is3D: true,
            colors: ['#0275d8', '#5cb85c', '#d9534f'],
        };

        const chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
let currentPage = 1;
let selectedMonth = '';

function drawBarChart(page = 1, month = '') {
    const url = month 
        ? `get_chart_data.php?page=${page}&month=${month}` 
        : `get_chart_data.php?page=${page}`; 

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.length === 0) {
                document.getElementById('chart_div').innerHTML = 'No data available.';
                return;
            }

            const chartData = [
                ['Employee', 'Completed', 'Did Not Finish', 'Abandoned']
            ];

            const taskMap = {};

            data.forEach(item => {
                const { employee_name, remarks, task_count } = item;

                if (!taskMap[employee_name]) {
                    taskMap[employee_name] = {
                        Completed: 0,
                        'Did Not Finish': 0,
                        Abandoned: 0,
                    };
                }

                if (taskMap[employee_name][remarks] !== undefined) {
                    taskMap[employee_name][remarks] += parseInt(task_count, 10);
                }
            });

            for (const [employee, counts] of Object.entries(taskMap)) {
                chartData.push([
                    employee,
                    counts.Completed || 0,
                    counts['Did Not Finish'] || 0,
                    counts.Abandoned || 0,
                ]);
            }

            const googleData = google.visualization.arrayToDataTable(chartData);

            const options = {
                title: month ? `Tasks by Employee (${month})` : 'Total Tasks by Employee',
                isStacked: true,
                hAxis: { title: 'Employee' },
                vAxis: { title: 'Task Count' },
                colors: ['#0275d8', '#5cb85c','#d9534f' ],
            };

            const chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(googleData, options);
        })
        .catch(error => {
            console.error('Error loading chart data:', error.message);
            const options = {
                title: month ? `Tasks by Employee (${month})` : 'Total Tasks by Employee',
                isStacked: true,
                hAxis: { title: 'Employee' },
                vAxis: { title: 'Task Count' },
                colors: ['#0275d8', '#5cb85c','#d9534f' ],
            };
        });
        setInterval(drawBarChart, 1000);
}

document.getElementById('month_filter').addEventListener('change', (event) => {
    selectedMonth = event.target.value;
    currentPage = 1; 
    drawBarChart(currentPage, selectedMonth);
});

document.getElementById('prev_page').addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        drawBarChart(currentPage, selectedMonth);
    }
});

document.getElementById('next_page').addEventListener('click', () => {
    currentPage++;
    drawBarChart(currentPage, selectedMonth);
});

drawBarChart();
</script>
</html>
