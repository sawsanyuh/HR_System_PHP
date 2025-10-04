<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - HR System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../public/assets/css/login.css">
</head>
<body>
<div class="container-fluid login-container d-flex align-items-center">
  <div class="row w-100">
    <div class="col-md-12 d-flex justify-content-end">
      <div class="login-card">
        <!-- Logo -->
        <img src="../../public/assets/images/download.webp" alt="HR System Logo" class="logo">

        <h3 class="text-center mb-4">HR System Login</h3>
        <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger text-center">
          Invalid username or password.
       </div>
        <?php endif; ?>

        <form method="post" action="../../controllers/authController.php">
          <div class="mb-4">
            <input type="text" class="form-control" name="username" placeholder="Enter Your Username" required>
          </div>

          <div class="mb-4">
            <input type="password" class="form-control" name="password" placeholder="Enter Your Password" required>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn ">Login</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

</body>
</html>
