<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login_form.php");
    exit();
}

require_once "../../config/db.php";

if(isset($_GET['approve'])) {
    $stmt = $pdo->prepare("UPDATE leaves SET status='approved' WHERE id=?");
    $stmt->execute([$_GET['approve']]);
    header("Location: list.php"); exit();
}

if(isset($_GET['reject'])) {
    $stmt = $pdo->prepare("UPDATE leaves SET status='rejected' WHERE id=?");
    $stmt->execute([$_GET['reject']]);
    header("Location: list.php"); exit();
}

$leaves = $pdo->query("
    SELECT l.*, e.first_name, e.last_name 
    FROM leaves l
    JOIN employees e ON l.employee_id = e.id
    ORDER BY l.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../shared/header.php"; ?>
<?php include "../shared/sidebar.php"; ?>

<div class="container mt-4" style="margin-left:260px;">
    <h2>Leave Requests</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Employee</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($leaves as $l): ?>
            <tr>
                <td><?= htmlspecialchars($l['first_name'] . " " . $l['last_name']) ?></td>
                <td><?= $l['start_date'] ?></td>
                <td><?= $l['end_date'] ?></td>
                <td><?= htmlspecialchars($l['reason']) ?></td>
                <td>
                    <?php if($l['status']=='pending') echo "<span class='badge bg-warning'>Pending</span>"; ?>
                    <?php if($l['status']=='approved') echo "<span class='badge bg-success'>Approved</span>"; ?>
                    <?php if($l['status']=='rejected') echo "<span class='badge bg-danger'>Rejected</span>"; ?>
                </td>
                <td>
                    <?php if($l['status']=='pending'): ?>
                        <a href="list.php?approve=<?= $l['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                        <a href="list.php?reject=<?= $l['id'] ?>" class="btn btn-danger btn-sm">Reject</a>
                    <?php else: ?>
                        <span class="text-muted">No actions</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include "../shared/footer.php"; ?>
