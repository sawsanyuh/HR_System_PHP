<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../auth/login_form.php");
    exit();
}

require_once __DIR__ . '/../../config/db.php';

$employee_id = $_SESSION['employee_id'];

$stmt = $pdo->prepare("SELECT * FROM attendance WHERE employee_id = :employee_id ORDER BY date DESC");
$stmt->execute(['employee_id' => $employee_id]);
$attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../shared/header.php'; ?>
<?php include '../shared/sidebar.php'; ?>

<div class="main-content" style="margin-left:250px; padding:24px; background-color:#f7f4f4; ">
  <h2>My Attendance</h2>
  <table class="table table-bordered mt-3">
    <thead>
      <tr>
        <th>Date</th>
        <th>Check In</th>
        <th>Check Out</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($attendance): ?>
        <?php foreach ($attendance as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['date']) ?></td>
            <td><?= htmlspecialchars($row['time_in']) ?></td>
            <td><?= htmlspecialchars($row['time_out']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="4" class="text-center">No attendance records found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include '../shared/footer.php'; ?>
