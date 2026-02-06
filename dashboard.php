<?php
session_start();
include('config.php');

// Check if user is logged in and is an owner
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit();
}

// Fetch some quick stats for the store
$product_count = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$staff_count = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='staff'")->fetch_assoc()['total'];
$low_stock = $conn->query("SELECT COUNT(*) as total FROM products WHERE current_stock < 5")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Owner Dashboard - M/S Pandey Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { height: 100vh; background: #2c3e50; color: white; padding-top: 20px; }
        .sidebar a { color: white; text-decoration: none; padding: 15px; display: block; }
        .sidebar a:hover { background: #34495e; }
        .stat-card { border: none; border-radius: 10px; color: white; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar">
            <h4 class="text-center">Pandey Store</h4>
            <hr>
            <a href="dashboard.php"><i class="fa fa-home"></i> Dashboard</a>
            <a href="manage-utensils.php"><i class="fa fa-spoon"></i> Inventory</a>
            <a href="sales-report.php"><i class="fa fa-file-invoice"></i> Sales Reports</a>
            <a href="manage-staff.php"><i class="fa fa-users"></i> Manage Staff</a>
            <a href="logout.php" class="text-danger"><i class="fa fa-sign-out"></i> Logout</a>
        </div>

        <div class="col-md-10 p-4">
            <h3>Owner Control Panel</h3>
            <p>Welcome back, <?php echo $_SESSION['username']; ?>!</p>
            
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card stat-card bg-primary p-3">
                        <h5>Total Utensils</h5>
                        <h2><?php echo $product_count; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card bg-success p-3">
                        <h5>Active Staff</h5>
                        <h2><?php echo $staff_count; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card bg-danger p-3">
                        <h5>Low Stock Items</h5>
                        <h2><?php echo $low_stock; ?></h2>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <h4>Recent Utensil Inventory</h4>
                <table class="table table-bordered bg-white">
                    <thead class="table-dark">
                        <tr>
                            <th>Product Name</th>
                            <th>Material</th>
                            <th>Current Stock</th>
                            <th>Price (Excl. GST)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $products = $conn->query("SELECT * FROM products LIMIT 5");
                        while($row = $products->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['p_name']}</td>
                                    <td>{$row['material']}</td>
                                    <td>{$row['current_stock']}</td>
                                    <td>₹{$row['price_before_tax']}</td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>