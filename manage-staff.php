<?php
session_start();
include('config.php');

// Security: Only Owner can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php"); exit();
}

// Handle Staff Deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE user_id = $id AND role = 'staff'");
    header("Location: manage-staff.php");
}

$staff_members = $conn->query("SELECT * FROM users WHERE role = 'staff'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Staff - Pandey Store</title>
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
            <a href="dashboard.php"><i class="fa fa-home me-2"></i> Dashboard</a>
            <a href="manage-utensils.php"><i class="fa fa-utensils me-2"></i> Inventory</a>
            <a href="sales-report.php"><i class="fa fa-file-invoice me-2"></i> Sales Reports</a>
            <a href="manage-staff.php" class="active"><i class="fa fa-users me-2"></i> Manage Staff</a>
            <a href="logout.php" class="text-danger mt-5"><i class="fa fa-sign-out me-2"></i> Logout</a>
        </div>

        <div class="col-md-10 content-area">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Manage Store Staff</h3>
                <p class="text-muted">View and manage staff accounts for M/S Pandey Store.</p>
            </div>

            <div class="card p-4">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($staff_members->num_rows > 0): ?>
                            <?php while($row = $staff_members->fetch_assoc()): ?>
                            <tr class="align-middle">
                                <td><span class="badge bg-secondary">#<?php echo $row['user_id']; ?></span></td>
                                <td class="fw-bold"><?php echo $row['full_name']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td class="text-center">
                                    <a href="?delete=<?php echo $row['user_id']; ?>" 
                                       class="btn btn-outline-danger btn-sm" 
                                       onclick="return confirm('Are you sure you want to remove this staff member?')">
                                        <i class="fa fa-user-times me-1"></i> Remove
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No staff members registered yet.</td>
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