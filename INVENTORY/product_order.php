<?php

    session_start();

    if(!isset($_SESSION['users'])) {
        header('location: login.php');
    }

    
    // get all products 

    $show_table = 'products';
    $products = include('database/show.php');    
    $products = json_encode($products); //convert array to string

?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Product - Inventory Management System</title>
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
                            <h1 class="section_header"><i class="fa fa-plus"></i> Order Product</h1>
                            <div>
                                <form action="database/save_order.php" method="POST">
                                    <div class="alignRight">
                                        <button type="button" class="orderBtn orderProductBtn" id="orderProductBtn">Add Another Product</button>
                                    </div>
                                    <div id="orderProductLists">
                                        <p id="noData" style="color: #9f9f9f;">No Products Selected.</p>
                                    </div>
                                    <div class="alignRight marginTop20">
                                        <button type="submit" class="orderBtn submitOrderProductBtn">Submit Order</button>
                                    </div>
                                </form>
                            </div>
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
    <?php include('partials/app-scripts.php'); ?>
    
<script>
    var products = <?= $products ?>;
    var counter = 0;

    function Script() {
        var vm = this;

        let productOptions = '\
            <div>\
                <label for="machine_name">MACHINE NAME</label>\
                <select name="products[]" class="productNameSelect" id="machine_name">\
                    <option value="">Select Product</option>\
                    INSERTPRODUCTHERE\
                </select>\
                <button class="appbtn removeOrderBtn">Remove</button>\
            </div>';

        this.initialize = function () {
            this.registerEvents();
            this.renderProductOptions();
        };

         this.renderProductOptions = function() {
            let optionHtml = '';
            products.forEach(product => { 
                
                optionHtml += '<option value="' + product.id + '">' + product.machine_name + '</option>';
            })
            productOptions = productOptions.replace('INSERTPRODUCTHERE', optionHtml);
        },

        this.registerEvents = function () {
            document.addEventListener('click', function (e) {
                targetElement = e.target; //target element

                if (targetElement.id === 'orderProductBtn') {
                    document.getElementById('noData').style.display = 'none';
                    
                    let orderProductListsContainer = document.getElementById('orderProductLists');
                    
                    orderProductListsContainer.innerHTML += '\
                        <div class="orderProductRow">\
                            ' + productOptions + '\
                            <div class="suppliersRows" id="supplierRows_' + counter + '" data-counter="' + counter + '">\
                            </div>\
                        </div>';

                    counter++;
                }
                
                //if the button is clicked
                
                if(targetElement.classList.contains('removeOrderBtn')){
                    let orderRow = targetElement.closest('.orderProductRow');
                    
                    //remove element
                    
                    console.log(orderRow);
                }
            });

            document.addEventListener('change', function (e) {
                targetElement = e.target; //target element
                classList = targetElement.classList;

                if (classList.contains('productNameSelect')) {
                    let pid = targetElement.value;

                    let counterId = targetElement.closest('.orderProductRow').querySelector('.suppliersRows').dataset.counter;

                    // Simulate AJAX request success
                   $.get('database/get_product_supplier.php', {id:pid}, function(suppliers){
                        
                        vm.renderSupplierRows(suppliers, counterId);
                        
                    }, 'json')

                }
            });
        };

        this.renderSupplierRows = function (suppliers, counterId) {
            let supplierRows = '';

            suppliers.forEach(supplier => {
                supplierRows += '\
                    <div class="row">\
                        <div style="width: 50%;">\
                            <p class="supplierName">' + supplier.supplier_name + '</p>\
                        </div>\
                        <div style="width: 50%;">\
                            <label for="quantity">Quantity </label>\
                            <input type="number" class="appFormInput" class="orderProductQty" id="quantity" placeholder="Enter Product Quantity" name="quantity['+ counterId +']['+ supplier.id +']" />\
                        </div>\
                    </div>';
            });

            let supplierRowContainer = document.getElementById('supplierRows_' + counterId);
            supplierRowContainer.innerHTML = supplierRows;
        };
    }

    (new Script()).initialize();
</script>

</body>
</html>
