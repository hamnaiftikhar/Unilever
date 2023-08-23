<?php
$sid = $_POST['sId']; // Change 'sId' to 'supplierId'
$supplier_name = $_POST['supplier_name'];
$supplier_location = $_POST['supplier_location'];
$email = $_POST['email'];

try {
    $sql = "UPDATE suppliers SET supplier_name=?, supplier_location=?, email=?, updated_at=? WHERE id=?";
    include('connection.php');
    $conn->prepare($sql)->execute([$supplier_name, $supplier_location, $email, date('Y-m-d h:i:s'), $sid]); // Use $sid instead of sId
    echo json_encode([
        'success' => true,
        'message' => $supplier_name . ' successfully updated.'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error processing your request!'
    ]);
}
?>
