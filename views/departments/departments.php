<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login_form.php");
    exit();
}

require_once "../../config/db.php";


$departments = $pdo->query("
    SELECT d.*, COUNT(e.id) as employee_count
    FROM departments d
    LEFT JOIN employees e ON e.department_id = d.id
    GROUP BY d.id
    ORDER BY d.name
")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../shared/header.php"; ?>
<?php include "../shared/sidebar.php"; ?>

<div class="container mt-4" style="margin-left:260px;">
    <h2 class="mb-3">Departments</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th class="d-none">ID</th>
                <th>Department Name</th>
                <th>Employees</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($departments as $dept): ?>
            <tr>
                <td class="d-none"><?= $dept['id'] ?></td>
                <td><?= htmlspecialchars($dept['name']) ?></td>
                <td><?= $dept['employee_count'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include "../shared/footer.php"; ?>
