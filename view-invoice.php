<?php
include('config.php');

if (!isset($_GET['id'])) {
    die("Invoice ID not found.");
}

$inv_id = $_GET['id'];

// 1. Fetch Invoice Details
$invoice_query = $conn->query("SELECT * FROM invoices WHERE inv_id = '$inv_id'");
$inv = $invoice_query->fetch_assoc();

// 2. Fetch Items for this Invoice
$items_query = $conn->query("SELECT ii.*, p.p_name, p.hsn_code 
                             FROM invoice_items ii 
                             JOIN products p ON ii.p_id = p.p_id 
                             WHERE ii.inv_id = '$inv_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?= $inv_id ?> - M/S Pandey Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
            .container { width: 100%; max-width: 100%; margin: 0; }
            .invoice-box { border: none; box-shadow: none; }
        }
        .invoice-box {
            max-width: 850px;
            margin: 50px auto;
            padding: 40px;
            border: 1px solid #dee2e6;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        .header-line { border-bottom: 2px solid #2c3e50; padding-bottom: 20px; margin-bottom: 30px; }
        .store-title { color: #2c3e50; font-weight: 800; letter-spacing: 1px; }
    </style>
</head>
<body>

<div class="container">
    <div class="no-print mt-4 text-center">
        <button onclick="window.print()" class="btn btn-primary px-4 shadow-sm">
            <i class="fa fa-print me-2"></i> Print Invoice
        </button>
        <a href="staff.php" class="btn btn-outline-secondary px-4 shadow-sm">
            Back to Dashboard
        </a>
    </div>

    <div class="invoice-box">
        <div class="row header-line">
            <div class="col-7">
                <h3 class="store-title mb-1">M/S PANDEY STORE</h3>
                <p class="mb-0 text-muted">New Market, Dibrugarh, Assam</p>
                <p class="mb-0 text-muted"><strong>GSTIN:</strong> 18AAAAA0000A1Z5</p>
                <p class="text-muted"><strong>Contact:</strong> +91 98765 43210</p>
            </div>
            <div class="col-5 text-end">
                <h1 class="text-muted fw-light mb-0">TAX INVOICE</h1>
                <p class="mb-0"><strong>Invoice No:</strong> #INV-<?= $inv['inv_id'] ?></p>
                <p><strong>Date:</strong> <?= date('d-M-Y', strtotime($inv['inv_date'])) ?></p>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <h6 class="text-uppercase text-secondary small fw-bold mb-2">Customer Details:</h6>
                <h5 class="mb-1"><?= strtoupper($inv['customer_name']) ?></h5>
                <p class="text-muted mb-0">Phone: <?= $inv['customer_contact'] ?></p>
            </div>
        </div>

        <table class="table table-bordered mb-4">
            <thead class="table-dark">
                <tr>
                    <th class="py-3">Utensil Description</th>
                    <th class="py-3 text-center">HSN</th>
                    <th class="py-3 text-end">Price</th>
                    <th class="py-3 text-center">Qty</th>
                    <th class="py-3 text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while($item = $items_query->fetch_assoc()): ?>
                <tr>
                    <td class="py-3"><?= $item['p_name'] ?></td>
                    <td class="py-3 text-center"><?= $item['hsn_code'] ?></td>
                    <td class="py-3 text-end">₹<?= number_format($item['unit_price'], 2) ?></td>
                    <td class="py-3 text-center"><?= $item['quantity'] ?></td>
                    <td class="py-3 text-end fw-bold">₹<?= number_format($item['item_total'], 2) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="row justify-content-end mt-5">
            <div class="col-md-5">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th class="text-secondary">Taxable Amount:</th>
                        <td class="text-end">₹<?= number_format($inv['total_taxable_amount'], 2) ?></td>
                    </tr>
                    <tr>
                        <th class="text-secondary">CGST (6%):</th>
                        <td class="text-end">₹<?= number_format($inv['total_cgst'], 2) ?></td>
                    </tr>
                    <tr>
                        <th class="text-secondary">SGST (6%):</th>
                        <td class="text-end">₹<?= number_format($inv['total_sgst'], 2) ?></td>
                    </tr>
                    <tr class="border-top">
                        <th class="pt-3"><h5>Grand Total:</h5></th>
                        <td class="text-end pt-3 text-primary"><h5>₹<?= number_format($inv['grand_total'], 2) ?></h5></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-5 pt-5 border-top text-center">
            <p class="mb-1 fw-bold">Thank you for shopping at M/S Pandey Store!</p>
            <p class="text-muted small">Quality utensils for your kitchen since 2026.</p>
        </div>
    </div>
</div>

</body>
</html>