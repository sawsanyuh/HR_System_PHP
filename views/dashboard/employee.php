<?php
session_start();

// Protect the page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../auth/login_form.php");
    exit();
}
?>

<?php include '../shared/header.php'; ?>
<?php include '../shared/sidebar.php'; ?>

<div class="main-content" style="margin-left:250px; padding:24px; background-color:#f7f4f4;">
  <h2>Hello, <?= htmlspecialchars($_SESSION['username']); ?> 👋</h2>
  <p>Welcome to your employee dashboard.</p>

 
  <div style="background:#fff; padding:20px; border-radius:8px; margin-bottom:20px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
    <h4>My Details</h4>
    <p><strong>Name:</strong> <?= htmlspecialchars($_SESSION['username']); ?></p>
    <p><strong>Role:</strong> Employee</p>
  </div>

 
  <div style="display:flex; gap:20px; flex-wrap:wrap;">
    
    <div style="flex:1; min-width:300px; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
      <h4>Attendance Summary</h4>
      <canvas id="attendanceChart" width="100" height="100"></canvas>
    </div>

    
    <div style="flex:1; min-width:300px; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
      <h4>Leave Status</h4>
      <canvas id="leaveChart" width="100" height="100"></canvas>
    </div>
  </div>
</div>

<?php include '../shared/footer.php'; ?>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

const ctx1 = document.getElementById('attendanceChart').getContext('2d');
new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: ['Present', 'Absent', 'Late'],
        datasets: [{
            data: [22, 2, 3], 
            backgroundColor: ['#28a745','#dc3545','#ffc107']
        }]
    }
});

const ctx2 = document.getElementById('leaveChart').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: ['Approved','Pending','Rejected'],
        datasets: [{
            label: 'Leaves',
            data: [3, 1, 1], 
            backgroundColor: ['#28a745','#ffc107','#dc3545']
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
