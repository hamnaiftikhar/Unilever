<?php

    session_start();

    if(!isset($_SESSION['users'])) {
        header('location: login.php');
    }
    $_SESSION['table'] = 'products';
    $_SESSION['redirect_to'] = 'product_add.php';

    $user = $_SESSION['users'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product - Inventory Management System</title>
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
                            <h1 class="section_header"><i class="fa fa-plus"></i> Create Product</h1>

                            <div id="userAddFormContainer">
                                <form action="database/add.php" method="POST" class="appForm">
                                <div class="appFormInputContainer">
                                    <label for="machine_name">Machine Name</label>
                                    <input type="text" class="appFormInput"
                                           id="machine_name" placeholder="Enter product name" name="machine_name" />
                                </div>
                                <div class="appFormInputContainer">
                                    <label for="description">Description</label>
                                    <textarea class="appFormInput productTextAreaInput" placeholder="Enter Product Description" id="description" name="description"></textarea>
                                </div>
                                <div class="appFormInputContainer">
                                    <label for="description">Supplier</label>
                                    <select name="suppliers[]" id="suppliersSelect" multiple="">
                                        <option value="">Select Supplier</option>
                                        <?php
                                            $show_table = 'suppliers';
                                            $suppliers = include('database/show.php');
                                            foreach($suppliers as $supplier){
                                                echo "<option value='" . $supplier['id'] . "'>" . $supplier['supplier_name'] . "</option>";
                                            }
                                        ?>
                                
                                    </select>
                                </div>
                                <div class="appFormInputContainer">
                                    <label for="location">Product Location</label>
                                    <input class="appFormInput" type="text" placeholder="Enter Product Location" id="location" name="location">
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
