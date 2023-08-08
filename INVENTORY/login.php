<?php 

    // start session
    session_start();
    if(isset($_SESSION['users'])) header('location: dashboard.php');
        
        
    $error_message = '';

    
    if($_POST){
        include('database/connection.php');
        $username = $_POST['username'];
        $password = $_POST['password'];  
        
        $query = 'SELECT * FROM user WHERE user.email="'. $username .'" AND user.password="'. $password . '" LIMIT 1';
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        if($stmt->rowCount() > 0){
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $user = $stmt->fetchAll()[0];
            $_SESSION['users'] = $user;
            
            
            header('Location: dashboard.php');
        } else $error_message = 'Please make sure you enter a correct username and password.';

    }
?>



<!DOCTYPE html>
<html>
<head>
    <title>Unilever Inventory Management System</title>
    
    <link rel="stylesheet" type="text/css" href="css/login.css">
    
</head>
<body id="login">
    <?php if(!empty($error_message)) { ?>
    
        <div id="errorMessage">
            <strong>ERROR:</strong> <p> <?- $error_message ?> </p>
        </div>

    <?php } ?>
    
    <div class="container">
        <div class="loginHeader">
            <div id="containerlogo">
                <div class="logo">
                    <img src="D:\UNILEVER_INTERNSHIP\INVENTORY\images\unilever.png" alt="Unilever Logo">
                </div>
            </div>
            <h1>Unilever</h1>
            <p>Inventory Management System</p>
        
        </div>
        <div class="loginBody">
            <form action="login.php" method="POST">
                <div class="loginInputContainer">
                    <label for="">Username</label>
                    <input placeholder="username" name="username" type="text" />
                </div>   
                <div class="loginInputContainer">
                    <label for="">Password</label>
                    <input placeholder="password" name="password" type="password" />
                </div>  
                <div class="loginButtonContainer">
                    <button>login</button>  
                </div>
                
            </form>
        </div>
    </div>
</body>
</html>