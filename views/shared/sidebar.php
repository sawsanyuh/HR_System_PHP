
<div class="sidebar d-flex flex-column p-3 bg-dark text-white">
<div class="d-flex align-items-center mb-4">
  <i class='bx bxs-building-house logo-icon me-2'></i>
  <span class="fs-5 fw-bold">Management</span>
</div>


  <ul class="nav nav-pills flex-column mb-auto">
    <?php if ($_SESSION['role'] === 'admin'): ?>
      <li class="nav-item">
        <a href="../dashboard/admin.php" class="nav-link text-white">
          <i class='bx bxs-dashboard me-2'></i> Dashboard
        </a>
      </li>
      <li>
        <a href="../employees/list.php" class="nav-link text-white">
          <i class='bx bxs-user-detail me-2'></i> Employees
        </a>
      </li>
      <li>
        <a href="../payroll/payroll.php" class="nav-link text-white">
          <i class='bx bx-money me-2'></i> Payroll
        </a>
      </li>
      <li>
        <a href="../leaves/leaveRequests.php" class="nav-link text-white">
          <i class='bx bxs-calendar-event me-2'></i> Leave Requests
        </a>
      </li>
      <li>
        <a href="../attendance/attendance.php" class="nav-link text-white">
          <i class='bx bxs-check-square me-2'></i> Attendance
        </a>
      </li>
      <li>
        <a href="../departments/departments.php" class="nav-link text-white">
          <i class='bx bxs-building me-2'></i> Departments
        </a>
      </li>

    <?php elseif ($_SESSION['role'] === 'employee'): ?>
      <li class="nav-item">
        <a href="../dashboard/employee.php" class="nav-link text-white">
          <i class='bx bxs-dashboard me-2'></i> Dashboard
        </a>
      </li>
      <li>
      <li>
        <a href="../leaves/request.php" class="nav-link text-white">
          <i class='bx bxs-calendar-plus me-2'></i> My Leave Requests
        </a>
      </li>
      <li>
        <a href="../attendance/employee_attendance.php" class="nav-link text-white">
          <i class='bx bxs-check-square me-2'></i> My Attendance
        </a>
      </li>
    <?php endif; ?>
  </ul>

  <div class="mt-auto text-center">
    <a href="../../controllers/logout.php" class="btn btn-outline-light btn-sm w-75">
      <i class='bx bx-log-out-circle me-1'></i> Logout
    </a>
  </div>
</div>

<style>
.sidebar {
  width: 250px;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
}

.logo {
  width: 60px;
  height: 60px;
  border-radius: 30%;
}

.nav .nav-link {
  margin-bottom: 10px;
  display: flex;
  align-items: center;
}

.nav .nav-link:hover {
  background-color: #53677bff;
}
.sidebar i {
  font-size: 25px;  
}
.logo-icon {
  font-size: 50px;   
  color: #6db462ff;       
}

</style>


