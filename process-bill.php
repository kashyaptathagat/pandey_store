<?php
include('config.php');
if (isset($_POST['save_bill'])) {
    $customer = $_POST['customer_name'];
    $contact = $_POST['customer_contact'];
    
    // Preliminary totals (real calc should be done server-side too)
    $grand_total = 0; 
    $total_tax = 0;

    // 1. Insert into Invoices
    $conn->query("INSERT INTO invoices (customer_name, customer_contact) VALUES ('$customer', '$contact')");
    $invoice_id = $conn->insert_id;

    // 2. Loop through items
    foreach ($_POST['p_id'] as $key => $p_id) {
        $qty = $_POST['qty'][$key];
        $price = $_POST['price'][$key];
        $gst_per = $_POST['gst'][$key];
        $item_total = $price * $qty;
        $item_tax = ($item_total * $gst_per / 100);

        $grand_total += ($item_total + $item_tax);
        $total_tax += $item_tax;

        // Insert into items table
        $conn->query("INSERT INTO invoice_items (inv_id, p_id, quantity, unit_price, item_total) 
                      VALUES ('$invoice_id', '$p_id', '$qty', '$price', '$item_total')");
        
        // REDUCE STOCK
        $conn->query("UPDATE products SET current_stock = current_stock - $qty WHERE p_id = '$p_id'");
    }

    // 3. Update main invoice with totals
    $cgst = $total_tax / 2;
    $sgst = $total_tax / 2;
    $conn->query("UPDATE invoices SET total_taxable_amount='$item_total', total_cgst='$cgst', total_sgst='$sgst', grand_total='$grand_total' WHERE inv_id='$invoice_id'");

    echo "<script>alert('Bill Generated Successfully!'); window.location='view_invoice.php?id=$invoice_id';</script>";
}
?>