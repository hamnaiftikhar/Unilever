<?php 
include('connection.php');

$id = $_GET['id'];

$stmt = $conn->prepare("
            SELECT supplier_name, suppliers.id
                FROM suppliers, productsuppliers
                WHERE
                    productsuppliers.product = $id
                        AND
                    productsuppliers.supplier = suppliers.id 
            ");
            
$stmt->execute();
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($suppliers);
?>