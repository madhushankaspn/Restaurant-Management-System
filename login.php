<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once 'classes/User.php';
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new User();
    $user->username = $_POST['credential']; // Can be username or email
    $user->password = $_POST['password'];
    
    if($user->login()) {
        $_SESSION['user_id'] = $user->u_id;
        $_SESSION['username'] = $user->username;
        $_SESSION['email'] = $user->email;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RMS Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Login to RMS</h4>
                    </div>
                    <div class="card-body">
                        <?php if($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="credential" class="form-label">Username or Email</label>
                                <input type="text" class="form-control" id="credential" name="credential" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Login</button>
                        </form>
                        <div class="mt-3 text-center">
                            Don't have an account? <a href="register.php">Register here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>