<?php
    //start sessiom
    session_start();
    
    $table_name = $_SESSION['table'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $encrypted = password_hash($password, PASSWORD_DEFAULT);
    
    try {
           $command = "INSERT INTO 
                            $table_name(first_name, last_name, email, password, created_at, updated_at)
                      VALUES 
                            ('".$first_name."', '".$last_name."', '".$email."', '".$encrypted."', NOW(), NOW())";

            include('connection.php');
            $conn->exec($command);
            $response = [
                'success' => true,
                'message' => $first_name . ' ' . $last_name . ' successfully added to the system.'
            ];

    } catch (PDOException $e) {
        $response = [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }

    $_SESSION['response'] = $response;
    header('location: ../user_add.php');
    //var_dump($_POST);
?>
