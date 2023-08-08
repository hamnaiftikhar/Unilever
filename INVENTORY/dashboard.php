<?php

    session_start();

    if(!isset($_SESSION['users'])) {
        header('location: login.php');
    }

    $user = $_SESSION['users'];

    
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Inventory Management System</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <script src="https://use.fontawesome.com/0c7a3095b5.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

</head>
<body>
    <div id="dashboardMainContainer">
        <?php include("partials/sidebar.php") ?>
        <div class="dashboard_content_container" id="dashboard_content_container">
            <?php include("partials/topnav.php") ?>
            <div class="dashboard_content">
                <div class="dashboard_content_main">
                </div>
            </div>
        </div>
    </div>
    
<script src="Javascript/script.js">
</script>
</body>
</html>
