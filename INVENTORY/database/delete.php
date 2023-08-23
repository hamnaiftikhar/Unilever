<?php
    $data = $_POST;
    $id = (int) $data['id'];
    $table = $data['table'];
    

    try {
        include('connection.php');
        
        // delete connected table productsuppliers
        
        if($table === 'suppliers'){
            //Delete  id
            $supplier_id = $id;
            
            $command = "DELETE FROM productsuppliers WHERE supplier={$id}";
            $conn->exec($command);
            
        }
        
        
           if($table === 'products'){
            //Delete  id
            $supplier_id = $id;
            
            $command = "DELETE FROM productsuppliers WHERE product={$id}";
            $conn->exec($command);
            
        }
            
            
        // Deleting main table
        $command = "DELETE FROM $table WHERE id={$id}";
        
        $conn->exec($command);
        
        echo json_encode([
            'success' => true,
        ]);

    } catch (PDOException $e) {
         echo json_encode([
            'success' => false,
        ]);

        
    }

?>