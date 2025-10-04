<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../../auth/login_form.php");
    exit();
}

require_once "../../config/db.php";

if (isset($_POST['request_leave'])) {
    $stmt = $pdo->prepare("INSERT INTO leaves (employee_id, start_date, end_date, reason) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_SESSION['employee_id'],
        $_POST['start_date'],
        $_POST['end_date'],
        $_POST['reason']
    ]);
    $success = true;
}

$leaves = $pdo->prepare("SELECT * FROM leaves WHERE employee_id=? ORDER BY created_at DESC");
$leaves->execute([$_SESSION['employee_id']]);
$leaves = $leaves->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../shared/header.php"; ?>
<?php include "../shared/sidebar.php"; ?>
<div class="main-content" style="margin-left:250px; padding:24px; background-color:#f7f4f4; ">
    <h2>Request Leave</h2>

    <?php if(isset($success)) echo "<div class='alert alert-success'>Leave request submitted!</div>"; ?>

    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>End Date</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Reason</label>
            <textarea name="reason" class="form-control"></textarea>
        </div>
        <button type="submit" name="request_leave" class="btn btn-primary">Submit Request</button>
    </form>

    <h3>My Leave History</h3>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Start</th>
                <th>End</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($leaves as $l): ?>
            <tr>
                <td><?= $l['start_date'] ?></td>
                <td><?= $l['end_date'] ?></td>
                <td><?= htmlspecialchars($l['reason']) ?></td>
                <td>
                    <?php if($l['status']=='pending') echo "<span class='badge bg-warning'>Pending</span>"; ?>
                    <?php if($l['status']=='approved') echo "<span class='badge bg-success'>Approved</span>"; ?>
                    <?php if($l['status']=='rejected') echo "<span class='badge bg-danger'>Rejected</span>"; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include "../shared/footer.php"; ?>
