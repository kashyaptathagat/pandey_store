<?php
include('config.php');

if (isset($_POST['register'])) {
    $full_name = $_POST['full_name'];
    $username  = $_POST['username'];
    $password  = $_POST['password']; // In a real app, use password_hash()

    // Check if username already exists
    $checkUser = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($checkUser);

    if ($result->num_rows > 0) {
        $error = "Username already taken! Please choose another.";
    } else {
        $sql = "INSERT INTO users (full_name, username, password) VALUES ('$full_name', '$username', '$password')";
        if ($conn->query($sql) === TRUE) {
            $success = "Staff registered successfully! <a href='login.php'>Login here</a>";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration - M/S Pandey Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: #f4f7f6; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .reg-card { width: 100%; max-width: 450px; padding: 30px; border-radius: 15px; background: #fff; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="reg-card">
    <h3 class="text-center mb-4">Staff Registration</h3>
    
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" placeholder="e.g. Rajesh Kumar" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Choose a unique username" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter a secure password" required>
        </div>
        <button type="submit" name="register" class="btn btn-success w-100">Register Staff</button>
        <div class="mt-3 text-center">
            <small>Already have an account? <a href="login.php">Login</a></small>
        </div>
    </form>
</div>

</body>
</html>