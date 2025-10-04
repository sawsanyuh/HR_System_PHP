<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login_form.php");
    exit();
}

require_once "../../config/db.php";
require_once "../../models/Employee.php";

$employeeModel = new Employee($pdo);
$employees = $employeeModel->all();

//  MARK ATTENDANCE 
if (isset($_POST['mark_attendance'])) {
    $employee_id = $_POST['employee_id'];
    $status      = $_POST['status'];
    $date        = date("Y-m-d");

    $check = $pdo->prepare("SELECT * FROM attendance WHERE employee_id=? AND date=?");
    $check->execute([$employee_id, $date]);

    if ($check->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO attendance (employee_id, date, status) VALUES (?, ?, ?)");
        $stmt->execute([$employee_id, $date, $status]);
    }
    header("Location: attendance.php");
    exit;
}

//  FETCH ATTENDANCE RECORD
$attendance_records = $pdo->query("
    SELECT a.id, e.first_name, e.last_name, a.date, a.status
    FROM attendance a
    JOIN employees e ON a.employee_id = e.id
    ORDER BY a.date DESC
")->fetchAll(PDO::FETCH_ASSOC);

//  MONTHLY REPORT 
$monthly_report = [];
if (isset($_POST['generate_report'])) {
    $month = $_POST['month']; 
    $stmt = $pdo->prepare("
        SELECT e.first_name, e.last_name, a.status, COUNT(*) as total_days
        FROM attendance a
        JOIN employees e ON a.employee_id = e.id
        WHERE DATE_FORMAT(a.date, '%Y-%m') = ?
        GROUP BY e.id, a.status
        ORDER BY e.first_name
    ");
    $stmt->execute([$month]);
    $monthly_report = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php include "../shared/header.php"; ?>
<?php include "../shared/sidebar.php"; ?>

<div class="container mt-4" style="margin-left:260px;">
    <h2 class="mb-3">Attendance Management</h2>


    <div class="card mb-4">
        <div class="card-header bg-dark text-white">Mark Daily Attendance</div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Employee</label>
                    <select name="employee_id" class="form-select" required>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['id'] ?>">
                                <?= htmlspecialchars($emp['first_name'] . " " . $emp['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="present">Present</option>
                        <option value="absent">Absent</option>
                        <option value="leave">Leave</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" name="mark_attendance" class="btn btn-primary px-4">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Attendance -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">Attendance Records</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendance_records as $record): ?>
                        <tr>
                            <td><?= htmlspecialchars($record['first_name'] . " " . $record['last_name']) ?></td>
                            <td><?= htmlspecialchars($record['date']) ?></td>
                            <td><?= ucfirst($record['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>


    <div class="card">
        <div class="card-header bg-secondary text-white">Monthly Attendance Report</div>
        <div class="card-body">
            <form method="POST" class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Select Month</label>
                    <input type="month" name="month" class="form-control" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" name="generate_report" class="btn btn-success px-4">Generate</button>
                </div>
            </form>

            <?php if (!empty($monthly_report)): ?>
                <h5>Report Results:</h5>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee</th>
                            <th>Status</th>
                            <th>Total Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($monthly_report as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']) ?></td>
                                <td><?= ucfirst($row['status']) ?></td>
                                <td><?= $row['total_days'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include "../shared/footer.php"; ?>
