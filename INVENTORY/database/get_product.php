<?php
    include('connection.php');


    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM products Where id=$id");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode($row);


?>