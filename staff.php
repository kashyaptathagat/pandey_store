<?php
session_start();
include('config.php');

// Security: Only Staff can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard - M/S Pandey Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #2c3e50; color: white; padding: 0; }
        .sidebar-header { padding: 20px; text-align: center; border-bottom: 1px solid #34495e; font-weight: bold; font-size: 1.2rem; }
        .sidebar a { color: #bdc3c7; text-decoration: none; padding: 15px 25px; display: block; transition: 0.3s; }
        .sidebar a:hover { background: #34495e; color: white; }
        .sidebar a.active { background: #34495e; color: white; border-left: 4px solid #0d6efd; }
        .content-area { padding: 30px; }
        .menu-card { border: none; border-radius: 12px; transition: 0.3s; cursor: pointer; }
        .menu-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .icon-box { font-size: 2.5rem; color: #0d6efd; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar">
            <div class="sidebar-header">Pandey Store</div>
            <a href="staff.php" class="active"><i class="fa fa-home me-2"></i> Dashboard</a>
            <a href="billing.php"><i class="fa fa-file-invoice-dollar me-2"></i> Billing</a>
            <a href="view-inventory.php"><i class="fa fa-utensils me-2"></i> Check Stock</a>
            <a href="logout.php" class="text-danger mt-5"><i class="fa fa-sign-out me-2"></i> Logout</a>
        </div>

        <div class="col-md-10 content-area">
            <div class="mb-4">
                <h3>Staff Portal</h3>
                <p class="text-muted">Welcome back, <strong><?php echo $_SESSION['username']; ?></strong>. Manage your daily utensil sales below.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <a href="billing.php" class="text-decoration-none text-dark">
                        <div class="card menu-card p-4 text-center shadow-sm">
                            <div class="icon-box"><i class="fa fa-plus-circle"></i></div>
                            <h4>New GST Bill</h4>
                            <p class="text-muted mb-0">Create and print customer invoices.</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="view-inventory.php" class="text-decoration-none text-dark">
                        <div class="card menu-card p-4 text-center shadow-sm">
                            <div class="icon-box text-success"><i class="fa fa-search"></i></div>
                            <h4>Check Stock</h4>
                            <p class="text-muted mb-0">Search utensils and availability.</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <div class="card menu-card p-4 text-center shadow-sm">
                        <div class="icon-box text-secondary"><i class="fa fa-user-shield"></i></div>
                        <h4>Account Role</h4>
                        <p class="text-muted mb-0">Currently Logged as: <strong>Staff</strong></p>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div class="card p-4 border-0 shadow-sm">
                    <h5><i class="fa fa-info-circle me-2 text-primary"></i> Daily Tip</h5>
                    <p class="mb-0 text-muted">Remember to check the HSN code (7323) on invoices for all stainless steel utensil sales to stay GST compliant.</p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>