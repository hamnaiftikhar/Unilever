<?php

$p_id = $_POST['pId'];
$machine_name = $_POST['machine_name']; // Corrected variable name
$description = $_POST['description'];
$location = $_POST['location'];

// get suppliers else make it empty
$suppliers = isset($_POST['suppliers']) ? $_POST['suppliers'] : [];


try {
    $sql = "UPDATE products SET machine_name=?, description=?, location=?, updated_at=? WHERE id=?";
    include('connection.php');
    $conn->prepare($sql)->execute([$machine_name, $description, $location, date('Y-m-d h:i:s'), $p_id]); // Reordered the values
    echo json_encode([
        'success' => true,
        'message' => $machine_name . ' successfully updated.' // Added concatenation operator
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error processing your request!'
    ]);
}
?>

