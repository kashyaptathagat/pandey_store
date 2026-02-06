<?php
session_start();
include('config.php');
if ($_SESSION['role'] !== 'owner') { header("Location: login.php"); exit(); }

if (isset($_POST['add_product'])) {
    $name = $_POST['p_name'];
    $material = $_POST['material'];
    $hsn = $_POST['hsn_code'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $gst = $_POST['gst'];

    $sql = "INSERT INTO products (p_name, material, hsn_code, price_before_tax, gst_percentage, current_stock) 
            VALUES ('$name', '$material', '$hsn', '$price', '$gst', '$stock')";
    
    if ($conn->query($sql)) {
        $msg = "Utensil added to inventory!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Utensil - Pandey Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card mx-auto shadow" style="max-width: 600px;">
        <div class="card-header bg-primary text-white"><h4>Add New Utensil</h4></div>
        <div class="card-body">
            <?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <label>Item Name (e.g., Stainless Steel Kadai 2L)</label>
                    <input type="text" name="p_name" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Material</label>
                        <select name="material" class="form-control">
                            <option>Stainless Steel</option>
                            <option>Aluminium</option>
                            <option>Copper</option>
                            <option>Brass</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>HSN Code</label>
                        <input type="text" name="hsn_code" class="form-control" value="7323">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Price (Base)</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>GST %</label>
                        <input type="number" name="gst" class="form-control" value="12">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Initial Stock</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>
                </div>
                <button type="submit" name="add_product" class="btn btn-primary w-100">Save to Inventory</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>