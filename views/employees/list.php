<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login_form.php");
    exit();
}

require_once "../../config/db.php";
require_once "../../models/Employee.php";

$departments = $pdo->query("SELECT * FROM departments ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['add_employee'])) {
    $data = [
        'first'  => $_POST['first_name'],
        'last'   => $_POST['last_name'],
        'email'  => $_POST['email'],
        'phone'  => $_POST['phone'],
        'salary' => $_POST['salary'],
        'department_id' => $_POST['department_id'] ?: null
    ];
    $stmt = $pdo->prepare("INSERT INTO employees (first_name,last_name,email,phone,salary,department_id) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$data['first'],$data['last'],$data['email'],$data['phone'],$data['salary'],$data['department_id']]);
    header("Location: list.php");
    exit;
}

// ---------- EDIT  ----------
if (isset($_POST['edit_employee'])) {
    $id = $_POST['id'];
    $data = [
        'first'  => $_POST['first_name'],
        'last'   => $_POST['last_name'],
        'email'  => $_POST['email'],
        'phone'  => $_POST['phone'],
        'salary' => $_POST['salary'],
        'department_id' => $_POST['department_id'] ?: null
    ];
    $stmt = $pdo->prepare("UPDATE employees SET first_name=?, last_name=?, email=?, phone=?, salary=?, department_id=? WHERE id=?");
    $stmt->execute([$data['first'],$data['last'],$data['email'],$data['phone'],$data['salary'],$data['department_id'],$id]);
    header("Location: list.php");
    exit;
}

// ---------- DELETE  ----------
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM employees WHERE id=?");
    $stmt->execute([$id]);
    header("Location: list.php");
    exit;
}

// ---------- FETCH EMPLOYEES ----------
$employees = $pdo->query("
    SELECT e.*, d.name AS department_name
    FROM employees e
    LEFT JOIN departments d ON e.department_id = d.id
    ORDER BY e.first_name
")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../shared/header.php"; ?>
<?php include "../shared/sidebar.php"; ?>

<div class="container mt-4" style="margin-left:260px;">
    <h2 class="mb-3">Employees</h2>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Add Employee</button>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Salary</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($employees as $emp): ?>
            <tr id="row-<?= $emp['id'] ?>">
                <form method="POST" style="margin:0;">
                    <input type="hidden" name="id" value="<?= $emp['id'] ?>">

                    <td>
                        <span class="text-field"><?= htmlspecialchars($emp['first_name']) ?></span>
                        <input type="text" name="first_name" class="form-control edit-input d-none" value="<?= htmlspecialchars($emp['first_name']) ?>">
                    </td>
                    
                    <td>
                        <span class="text-field"><?= htmlspecialchars($emp['last_name']) ?></span>
                        <input type="text" name="last_name" class="form-control edit-input d-none" value="<?= htmlspecialchars($emp['last_name']) ?>">
                    </td>
                    
                    <td>
                        <span class="text-field"><?= htmlspecialchars($emp['email']) ?></span>
                        <input type="email" name="email" class="form-control edit-input d-none" value="<?= htmlspecialchars($emp['email']) ?>">
                    </td>
                    
                    <td>
                        <span class="text-field"><?= htmlspecialchars($emp['phone']) ?></span>
                        <input type="text" name="phone" class="form-control edit-input d-none" value="<?= htmlspecialchars($emp['phone']) ?>">
                    </td>
                    
                    <td>
                        <span class="text-field"><?= htmlspecialchars($emp['salary']) ?></span>
                        <input type="number" step="0.01" name="salary" class="form-control edit-input d-none" value="<?= htmlspecialchars($emp['salary']) ?>">
                    </td>

                    <td>
                        <span class="text-field"><?= htmlspecialchars($emp['department_name'] ?: '-') ?></span>
                        <select name="department_id" class="form-control edit-input d-none">
                            <option value="">-- Select Department --</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>" <?= ($emp['department_id'] == $dept['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dept['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>

                    <td>
                        <button type="button" class="btn btn-primary btn-sm edit-btn px-3">Edit</button>
                        <button type="submit" name="edit_employee" class="btn btn-success btn-sm save-btn d-none">Save</button>
                        <a href="list.php?delete_id=<?= $emp['id'] ?>" onclick="return confirm('Delete this employee?')" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>


<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>First Name</label>
          <input type="text" name="first_name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Last Name</label>
          <input type="text" name="last_name" class="form-control">
        </div>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Phone</label>
          <input type="text" name="phone" class="form-control">
        </div>
        <div class="mb-3">
          <label>Salary</label>
          <input type="number" step="0.01" name="salary" class="form-control">
        </div>
        <div class="mb-3">
          <label>Department</label>
          <select name="department_id" class="form-control">
              <option value="">-- Select Department --</option>
              <?php foreach ($departments as $dept): ?>
                  <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
              <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_employee" class="btn btn-primary">Add Employee</button>
      </div>
    </form>
  </div>
</div>

<script>
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function() {
        const row = button.closest('tr');
        row.querySelectorAll('.text-field').forEach(span => span.classList.add('d-none'));
        row.querySelectorAll('.edit-input').forEach(input => input.classList.remove('d-none'));
        row.querySelector('.save-btn').classList.remove('d-none');
        button.classList.add('d-none');
    });
});
</script>

<?php include "../shared/footer.php"; ?>
