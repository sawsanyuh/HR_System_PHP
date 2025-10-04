<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /hr_system/views/auth/login_form.php");
    exit();
}

require_once "../../config/db.php";

// Fetch data for charts
// 1. Employees by Department
$deptData = $pdo->query("
    SELECT d.name, COUNT(e.id) as emp_count 
    FROM departments d
    LEFT JOIN employees e ON e.department_id = d.id
    GROUP BY d.id
")->fetchAll(PDO::FETCH_ASSOC);

// 2. Today's Attendance
$today = date('Y-m-d');
$attData = $pdo->query("
    SELECT status, COUNT(*) as count 
    FROM attendance 
    WHERE date = '$today'
    GROUP BY status
")->fetchAll(PDO::FETCH_ASSOC);

// Prepare arrays for charts
$deptNames = [];
$deptCounts = [];
foreach ($deptData as $row) {
    $deptNames[] = $row['name'];
    $deptCounts[] = $row['emp_count'];
}

$attLabels = [];
$attCounts = [];
foreach ($attData as $row) {
    $attLabels[] = ucfirst($row['status']);
    $attCounts[] = $row['count'];
}
?>

<?php include __DIR__ . '/../shared/header.php'; ?>
<?php include __DIR__ . '/../shared/sidebar.php'; ?>

<div class="main-content" style="margin-left:250px; padding:24px; background-color:#f7f4f4; ">
    <h1 class="mt-4 mb-4">Admin Dashboard</h1>

    <div class="row g-4">

        <!-- Employees by Department -->
        <div class="col-md-6">
            <div class="card p-3 shadow-sm" style="height: 290px;width: 470px;">
                <h5>Employees by Department</h5>
                <canvas id="deptChart" height="250"></canvas>
            </div>
        </div>

        <!--  Attendance -->
        <div class="col-md-6">
            <div class="card p-3 shadow-sm" style="height: 290px;width: 470px;">
                <h5>Today's Attendance (<?= $today ?>)</h5>
                <canvas id="attChart" height="200"></canvas>
            </div>
        </div>

        <!-- Payroll -->
        <div class="col-md-9">
            <div class="card p-3 shadow-sm " style="height: 350px;width: 1090px;">
                <h5>Payroll Summary (Last 6 Months)</h5>
                <canvas id="payrollChart" height="250"></canvas>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

const deptCtx = document.getElementById('deptChart').getContext('2d');
const deptChart = new Chart(deptCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($deptNames) ?>,
        datasets: [{
            label: 'Number of Employees',
            data: <?= json_encode($deptCounts) ?>,
            backgroundColor: 'rgba(7, 86, 138, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});

const attCtx = document.getElementById('attChart').getContext('2d');
const attChart = new Chart(attCtx, {
    type: 'pie',
    data: {
        labels: <?= json_encode($attLabels) ?>,
        datasets: [{
            label: 'Attendance',
            data: <?= json_encode($attCounts) ?>,
            backgroundColor: [
                'rgba(75, 192, 192, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(255, 206, 86, 0.7)'
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

const payrollCtx = document.getElementById('payrollChart').getContext('2d');
const payrollChart = new Chart(payrollCtx, {
    type: 'line',
    data: {
        labels: ["<?= date('M', strtotime('-5 month')) ?>", "<?= date('M', strtotime('-4 month')) ?>", "<?= date('M', strtotime('-3 month')) ?>", "<?= date('M', strtotime('-2 month')) ?>", "<?= date('M', strtotime('-1 month')) ?>", "<?= date('M') ?>"],
        datasets: [{
            label: 'Total Payroll ($)',
            data: [12000, 15000, 14000, 16000, 15500, 16500],
            fill: false,
            borderColor: 'rgba(153, 102, 255, 1)',
            tension: 0.3,
            
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

<?php include __DIR__ . '/../shared/footer.php'; ?>
