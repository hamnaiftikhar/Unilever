<?php
    //start sessiom
    session_start();
    
    // Capture the table mappings
    include('table_columns.php');


    // Capture the table name
    $table_name = $_SESSION['table'];
    $columns = $table_column_mapping[$table_name];

    
    //looop through the columns

    $db_arr = [];
    $user = $_SESSION['users'];
    foreach($columns as $column){
        if(in_array($column, ['created_at', 'updated_at'])) $value = date('Y-m-d H:i:s');
        else if($column == 'created_by') $value = $user['id'];
        else if($column == 'password') $value = password_hash($_POST[$column], PASSWORD_DEFAULT);
            
        else $value = isset($_POST[$column]) ? $_POST[$column]: ''; 
        
        $db_arr[$column] = $value;
        
    }

    $table_properties = implode(",",array_keys($db_arr));
    $table_placeholders = ':' . implode(", :", array_keys($db_arr));

    



    // add the record to main table

    try {
           $sql = "INSERT INTO 
                            $table_name($table_properties)
                      VALUES 
                            ($table_placeholders)";

            include('connection.php');
            
            $stmt = $conn->prepare($sql); 
            $stmt->execute($db_arr);
            // Get saved id
            $product_id = $conn->lastInsertId();
        
        
            //add suppliers
            if($table_name === 'products'){
                $suppliers = isset($_POST['suppliers']) ? $_POST['suppliers'] : [];
                if($suppliers){
                   // loop thru the suppliers and add record
                    foreach($suppliers as $supplier){
                        $supplier_data = [
                            'supplier_id' => $supplier,
                            'product_id' => $product_id,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                            
                        
                       $sql = "INSERT INTO productsuppliers
                                        (supplier, product, updated_at, created_at)
                                  VALUES 
                                        (:supplier_id, :product_id, :updated_at, :created_at)";

                        $stmt = $conn->prepare($sql); 

                        $stmt->execute($supplier_data);
                    }
                }
            }
        
        
            $response = [
                'success' => true,
                'message' => 'Successfully added to the system.'
            ];

    } catch (PDOException $e) {
        $response = [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }

    $_SESSION['response'] = $response;
    header('location: ../' . $_SESSION['redirect_to']);
    //var_dump($_POST);
?>
