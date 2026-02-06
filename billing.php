<?php
session_start();
include('config.php');

// Security: Ensure user is logged in
if (!isset($_SESSION['role'])) { 
    header("Location: login.php"); 
    exit(); 
}

// 1. Fetch products for the dropdown
$products_result = $conn->query("SELECT * FROM products WHERE current_stock > 0");
$products = [];
while($row = $products_result->fetch_assoc()) { $products[] = $row; }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GST Billing - Pandey Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                <a href="view-inventory.php"><i class="fa fa-search me-2"></i> Check Stock</a>
            <?php endif; ?>
            <a href="billing.php" class="active"><i class="fa fa-file-invoice-dollar me-2"></i> Billing</a>
            <a href="logout.php" class="text-danger mt-5"><i class="fa fa-sign-out me-2"></i> Logout</a>
        </div>

        <div class="col-md-10 content-area">
            <div class="mb-4">
                <h3>Create New GST Bill</h3>
                <p class="text-muted">Generate professional invoices for M/S Pandey Store customers.</p>
            </div>

            <div class="card p-4">
                <form action="process-bill.php" method="POST">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Customer Name</label>
                            <input type="text" name="customer_name" class="form-control" placeholder="Enter name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Contact Number</label>
                            <input type="text" name="customer_contact" class="form-control" placeholder="Enter phone number">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="billTable">
                            <thead class="table-dark">
                                <tr>
                                    <th width="40%">Utensil Item</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>GST %</th>
                                    <th>Total</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="p_id[]" class="form-select product-select" required>
                                            <option value="">Select Utensil</option>
                                            <?php foreach($products as $p): ?>
                                                <option value="<?= $p['p_id'] ?>" data-price="<?= $p['price_before_tax'] ?>" data-gst="<?= $p['gst_percentage'] ?>">
                                                    <?= $p['p_name'] ?> (Stock: <?= $p['current_stock'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td><input type="number" name="price[]" class="form-control price" readonly></td>
                                    <td><input type="number" name="qty[]" class="form-control qty" value="1" min="1"></td>
                                    <td><input type="number" name="gst[]" class="form-control gst" readonly></td>
                                    <td><input type="number" name="total[]" class="form-control total" readonly></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm removeRow"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" id="addRow" class="btn btn-outline-primary btn-sm mb-4"><i class="fa fa-plus me-1"></i> Add Item</button>

                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded shadow-sm border">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span class="fw-bold">₹<span id="subtotal">0.00</span></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 text-primary">
                                    <span>GST (6% + 6%):</span>
                                    <span class="fw-bold">₹<span id="tax_amount">0.00</span></span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-4">
                                    <span class="h5">Grand Total:</span>
                                    <span class="h5 text-success">₹<span id="grand_total">0.00</span></span>
                                </div>
                                <button type="submit" name="save_bill" class="btn btn-success w-100 py-2 fw-bold">
                                    <i class="fa fa-print me-2"></i> Confirm & Print Bill
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Add new row
    $("#addRow").click(function() {
        var newRow = $("#billTable tbody tr:first").clone();
        newRow.find('input').val('');
        newRow.find('.qty').val(1);
        $("#billTable tbody").append(newRow);
    });

    // Remove row
    $(document).on('click', '.removeRow', function() {
        if($("#billTable tbody tr").length > 1) {
            $(this).closest('tr').remove();
            calculateTotal();
        }
    });

    // Calculate on change
    $(document).on('change', '.product-select, .qty', function() {
        var row = $(this).closest('tr');
        var selected = row.find('.product-select option:selected');
        var price = selected.data('price');
        var gstPer = selected.data('gst');
        var qty = row.find('.qty').val();

        row.find('.price').val(price);
        row.find('.gst').val(gstPer);
        
        var total = (price * qty);
        row.find('.total').val(total.toFixed(2));
        calculateTotal();
    });

    function calculateTotal() {
        var sub = 0;
        var tax = 0;
        $(".total").each(function() {
            var val = parseFloat($(this).val()) || 0;
            var rowGst = parseFloat($(this).closest('tr').find('.gst').val()) || 0;
            sub += val;
            tax += (val * rowGst / 100);
        });
        $("#subtotal").text(sub.toFixed(2));
        $("#tax_amount").text(tax.toFixed(2));
        $("#grand_total").text((sub + tax).toFixed(2));
    }
});
</script>
</body>
</html>