<?php
session_start();
require_once '../config/db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
       
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['role']      = $user['role'];
        $_SESSION['employee_id'] = $user['employee_id']; 


       
        if ($user['role'] === 'admin') {
            header("Location: ../views/dashboard/admin.php");
        } else {
            header("Location: ../views/dashboard/employee.php");
        }
        exit();
    } else {
        
        header("Location: ../views/auth/login_form.php?error=1");
        exit();
    }
} else {
    header("Location: ../views/auth/login_form.php");
    exit();
}
