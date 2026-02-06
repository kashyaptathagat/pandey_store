<?php
session_start();
include('config.php');

// Security: Only Owner can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php"); exit();
}

// Fetch total sales stats
$stats = $conn->query("SELECT SUM(grand_total) as total_rev, SUM(total_cgst + total_sgst) as total_tax FROM invoices")->fetch_assoc();
$invoices = $conn->query("SELECT * FROM invoices ORDER BY inv_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Reports - Pandey Store</title>
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
        .stat-card { color: white; border-radius: 10px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar">
            <div class="sidebar-header">Pandey Store</div>
            <a href="dashboard.php"><i class="fa fa-home me-2"></i> Dashboard</a>
            <a href="manage-utensils.php"><i class="fa fa-utensils me-2"></i> Inventory</a>
            <a href="sales-report.php" class="active"><i class="fa fa-file-invoice me-2"></i> Sales Reports</a>
            <a href="manage-staff.php"><i class="fa fa-users me-2"></i> Manage Staff</a>
            <a href="logout.php" class="text-danger mt-5"><i class="fa fa-sign-out me-2"></i> Logout</a>
        </div>

        <div class="col-md-10 content-area">
            <div class="mb-4">
                <h3>Sales & Tax Reports</h3>
                <p class="text-muted">Track your utensil store revenue and GST filings.</p>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card stat-card bg-primary p-4">
                        <h5>Total Revenue (Including Tax)</h5>
                        <h2>₹<?php echo number_format($stats['total_rev'] ?? 0, 2); ?></h2>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card stat-card bg-warning text-dark p-4">
                        <h5>Total GST Collected (CGST + SGST)</h5>
                        <h2>₹<?php echo number_format($stats['total_tax'] ?? 0, 2); ?></h2>
                    </div>
                </div>
            </div>

            <div class="card p-4">
                <h5 class="mb-3">Recent Invoices</h5>
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Invoice ID</th>
                            <th>Customer Name</th>
                            <th>Date</th>
                            <th>CGST (6%)</th>
                            <th>SGST (6%)</th>
                            <th>Grand Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($invoices->num_rows > 0): ?>
                            <?php while($inv = $invoices->fetch_assoc()): ?>
                            <tr class="align-middle">
                                <td><span class="badge bg-secondary">#INV-<?php echo $inv['inv_id']; ?></span></td>
                                <td><?php echo $inv['customer_name']; ?></td>
                                <td><?php echo date('d M Y', strtotime($inv['inv_date'])); ?></td>
                                <td>₹<?php echo number_format($inv['total_cgst'], 2); ?></td>
                                <td>₹<?php echo number_format($inv['total_sgst'], 2); ?></td>
                                <td class="fw-bold">₹<?php echo number_format($inv['grand_total'], 2); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No sales recorded yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>