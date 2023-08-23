<?php

    session_start();

    if(!isset($_SESSION['users'])) {
        header('location: login.php');
    }
    $_SESSION['table'] = 'suppliers';
    $_SESSION['redirect_to'] = 'supplier_add.php';

    $user = $_SESSION['users'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Supplier - Inventory Management System</title>
    <?php include('partials/app-header-scripts.php');  ?>
 <!--   <script src="https://kit.fontawesome.com/74f27641c2.js" crossorigin="anonymous"></script> -->
    
</head>
<body>
    <div id="dashboardMainContainer">
        <?php include("partials/sidebar.php") ?>
        <div class="dashboard_content_container" id="dashboard_content_container">
            <?php include("partials/topnav.php") ?>
            <div class="dashboard_content">
                <div class="dashboard_content_main">
                    <div class="row">
                        <div class="column column-12"> 
                            <h1 class="section_header"><i class="fa fa-plus"></i> Create Supplier</h1>

                            <div id="userAddFormContainer">
                                <form action="database/add.php" method="POST" class="appForm">
                                <div class="appFormInputContainer">
                                    <label for="supplier_name">Supplier Name</label>
                                    <input type="text" class="appFormInput"
                                           id="supplier_name" placeholder="Enter Supplier name" name="supplier_name" />
                                </div>
                                <div class="appFormInputContainer">
                                    <label for="supplier_location">Location</label>
                                    <input type="text" class="appFormInput" placeholder="Enter Supplier Location" id="supplier_location" name="supplier_location">
                                </div>
                                <div class="appFormInputContainer">
                                    <label for="email">Email Address</label>
                                    <input type="text" class="appFormInput" placeholder="Enter Supplier Email" id="email" name="email">
                                </div>
                                <button type="submit" class="appBtn"><i class="fa fa-plus"></i>Add Product</button>
                            </form>

                            <?php if(isset($_SESSION['response'])) {
                                    $response_message = $_SESSION['response']['message'];
                                    $is_success = $_SESSION['response']['success'];
                                ?>
                                <div class="responseMessage">
                                    <p class="responseMessage <?= $is_success ? 'responseMessage_success' : 'responseMessage_error' ?>" >
                                        <?= $response_message ?>
                                    </p>
                                </div>
                            <?php unset($_SESSION['response']); } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('partials/app-scripts.php'); ?>
</body>
</html>
