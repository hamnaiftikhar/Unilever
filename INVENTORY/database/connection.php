<?php 
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    
    //Connection to database
    try {
        $conn = new PDO("mysql:host=$servername;dbname=inventory" , $username, $password);
        //SET THE PDO ERROR MODE TO EXCEPTION
        
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            
    } catch (\Exception $e) {
        $error_message = $e->getMessage();
    } 

?>
 