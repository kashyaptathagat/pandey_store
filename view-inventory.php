<?php
session_start();
include('config.php');

// Security: Only logged-in users (Staff/Owner) can access
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM products WHERE p_name LIKE '%$search%' OR material LIKE '%$search%'";
} else {
    $query = "SELECT * FROM products";
}
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check Stock - M/S Pandey Store</title>
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
        .card { border: none; border-radius: 10px; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar">
            <div class="sidebar-header">Pandey Store</div>
            <?php if($_SESSION['role'] == 'owner'): ?>
                <a href="dashboard.php"><i class="fa fa-home me-2"></i> Dashboard</a>
                <a href="manage-utensils.php"><i class="fa fa-utensils me-2"></i> Inventory</a>
                <a href="sales-report.php"><i class="fa fa-file-invoice me-2"></i> Sales Reports</a>
                <a href="manage-staff.php"><i class="fa fa-users me-2"></i> Manage Staff</a>
            <?php else: ?>
                <a href="staff.php"><i class="fa fa-home me-2"></i> Dashboard</a>
                <a href="billing.php"><i class="fa fa-file-invoice-dollar me-2"></i> Billing</a>
            <?php endif; ?>
            <a href="view-inventory.php" class="active"><i class="fa fa-search me-2"></i> Check Stock</a>
            <a href="logout.php" class="text-danger mt-5"><i class="fa fa-sign-out me-2"></i> Logout</a>
        </div>

        <div class="col-md-10 content-area">
            <div class="mb-4">
                <h3>Utensil Stock List</h3>
                <p class="text-muted">Search available inventory and check real-time stock levels.</p>
            </div>

            <div class="card p-4">
                <form class="mb-4 d-flex" method="GET">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name (e.g. Cooker) or material (e.g. Steel)..." value="<?php echo $search; ?>">
                        <button class="btn btn-primary px-4">Search Inventory</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Utensil Item</th>
                                <th>Material</th>
                                <th>Stock Status</th>
                                <th>Base Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo $row['p_name']; ?></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo $row['material']; ?></span></td>
                                    <td>
                                        <?php if($row['current_stock'] > 0): ?>
                                            <span class="badge bg-success-subtle text-success px-3 py-2 border border-success-subtle">
                                                <i class="fa fa-check-circle me-1"></i> <?php echo $row['current_stock']; ?> In Stock
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger px-3 py-2 border border-danger-subtle">
                                                <i class="fa fa-times-circle me-1"></i> Out of Stock
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-primary fw-bold">₹<?php echo number_format($row['price_before_tax'], 2); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fa fa-box-open fa-3x mb-3"></i><br>
                                        No utensils found matching "<strong><?php echo $search; ?></strong>"
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>