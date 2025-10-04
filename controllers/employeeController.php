
<?php


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login_form.php");
    exit();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Employee.php';

$employeeModel = new Employee($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_employee'])) {
    $data = [
        'first'  => trim($_POST['first_name']),
        'last'   => trim($_POST['last_name']),
        'email'  => trim($_POST['email']),
        'phone'  => trim($_POST['phone']),
        'salary' => trim($_POST['salary']),
    ];
    $employeeModel->create($data);
    header("Location: ../views/employees/list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_employee'])) {
    $id = (int) $_POST['id'];
    $data = [
        'first'  => trim($_POST['first_name']),
        'last'   => trim($_POST['last_name']),
        'email'  => trim($_POST['email']),
        'phone'  => trim($_POST['phone']),
        'salary' => trim($_POST['salary']),
    ];
    $employeeModel->update($id, $data);
    header("Location: ../views/employees/list.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id'];
    $employeeModel->delete($id);
    header("Location: ../views/employees/list.php");
    exit();
}

$employees = $employeeModel->all();


