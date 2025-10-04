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

if (isset($_POST['download_payslip'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE id=?");
    $stmt->execute([$id]);
    $emp = $stmt->fetch(PDO::FETCH_ASSOC);

    $content = "Payslip\n";
    $content .= "-------------------------\n";
    $content .= "Employee ID: " . $emp['id'] . "\n";
    $content .= "Name: " . $emp['first_name'] . " " . $emp['last_name'] . "\n";
    $content .= "Email: " . $emp['email'] . "\n";
    $content .= "Phone: " . $emp['phone'] . "\n";
    $content .= "Basic Salary: $" . $emp['salary'] . "\n";
    $content .= "-------------------------\n";

    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="Payslip_' . $emp['first_name'] . '_' . $emp['last_name'] . '.txt"');
    echo $content;
    exit;
}
?>

<?php include "../shared/header.php"; ?>
<?php include "../shared/sidebar.php"; ?>

<div class="container mt-4" style="margin-left:260px;">
    <h2 class="mb-3">Payroll</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Basic Salary ($)</th>
                <th> Download</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $emp): ?>
            <tr>
                <form method="POST" style="margin:0;">
                    <td><?= htmlspecialchars($emp['first_name'] . " " . $emp['last_name']) ?></td>
                    <td><?= htmlspecialchars($emp['salary']) ?></td>
                    <td>
                        <button type="submit" name="download_payslip" class="btn btn-success btn-sm px-3">Download Payslip</button>
                    </td>
                </form>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include "../shared/footer.php"; ?>
