<?php
//
//session_start();
//require 'db.php';
//$newHash = password_hash('123456', PASSWORD_DEFAULT);
//die($newHash);
//$error = '';
//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//    if (empty($_POST['email'])) {
//        $error .= ' Email Is Required';
//    }
//    if (empty($_POST['password'])) {
//        $error .= ' Password Is Required';
//    }
//    if (!$error) {
//
//        $email = $_POST['email'];
//        $password = $_POST['password'];
//
//        $stmt = $conn->prepare("SELECT * FROM `admins` WHERE `email` = ? ");
//        $stmt->execute([$email]);
//        $user = $stmt->fetch();
//        if ($user && password_verify($password, $user['password'])) {
//
//            session_regenerate_id(true);
//            $_SESSION['admin_id'] = $user['id'];
//            $_SESSION['admin_name'] = $user['name'];
//            $date = date('Y-M', strtotime($user['created_at']));
//            $_SESSION['admin_created_at'] = $date;
//
//            header('location: dashboard.php');
//        }
//        $error = 'Invalid Username Or Password';
//    }
//}
//
//?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>login</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../css/icheck-bootstrap.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../css/adminlte.css">

</head>
<body  class="hold-transition login-page">

<div class="login-box">
    <div class="login-logo">
        <b>LogIN</b> Page
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your DashBord</p>

            <?php session_start(); ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger text-center">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="login_process.php">
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <!-- /.social-auth-links -->
            <br>
            <p class="mb-1">
                <a href="forgot-password.html">I forgot my password</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->
</body >
</html>
<?php
