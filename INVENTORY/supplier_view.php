<?php

    session_start();

    if(!isset($_SESSION['users'])) {
        header('location: login.php');
    }

    $show_table = 'suppliers';
//    $_SESSION['table'] = 'products';
    $suppliers = include('database/show.php');    
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Suppliers - Inventory Management System</title>
    <?php include('partials/app-header-scripts.php'); ?>
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
                            <h1 class="section_header"><i class="fa fa-list"></i> List of Suppliers</h1>
                            <div class="section_content">
                                <div class="users">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Supplier Name</th>
                                                <th>Supplier Location</th>
                                                <th>Contact Details</th>
                                                <th>Products</th>
                                                <th>Created By</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($suppliers as $index => $supplier){ ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td class="supplier_name"><?= $supplier['supplier_name'] ?></td>
                                                    <td class="supplier_location"><?= $supplier['supplier_location'] ?></td>
                                                    <td class="email"><?= $supplier['email'] ?></td>
                                                    <td>
                                                        <?php
                                                            $product_list = '-';
                                                            $sid = $supplier['id'];
                                                            $stmt = $conn->prepare("
                                                            SELECT machine_name
                                                                FROM products, productsuppliers 
                                                            Where 
                                                                productsuppliers.supplier=$sid
                                                                    AND 
                                                                productsuppliers.product= products.id
                                                                
                                                                ");
                                                            $stmt->execute();
                                                            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                                                           
                                                            if($row){
                                                                $product_arr = array_column($row,'machine_name');
                                                                $product_list = '<li>' .  implode("</li><li>", $product_arr);
                                                            }                               
                                                                                           
                                                            
                                                            echo $product_list;                             
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $uid = $supplier['created_by'];
                                                            $stmt = $conn->prepare("SELECT * FROM user Where id=$uid");
                                                            $stmt->execute();
                                                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            $created_by_name = $row['first_name'] . '' . $row['last_name'];
                                                            echo $created_by_name;
                                                                                           
                                                        ?>
                                                    </td>
                                                    <td><?= date('M d,Y @ h:i:s A', strtotime($supplier['created_at'])) ?></td>
                                                    <td><?= date('M d,Y @ h:i:s A', strtotime($supplier['updated_at'])) ?></td>
                                                    <td>
                                                        <a href="" class="updateSupplier" data-sid="<?= $supplier['id'] ?>"><i class="fa fa-pencil"></i> Edit</a>
                                                        <a href="" class="deleteSupplier" data-name="<?= $supplier['supplier_name'] ?>" data-sid="<?= $supplier['id'] ?>"><i class="fa fa-trash"></i> Delete</a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <p class="userCount"><?= count($suppliers) ?> suppliers </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   
    
    <?php include('partials/app-scripts.php'); ?>
    

    
 <!-- JScript Code For Updating / Deleting Products-->   
    
<script>
function script() {
    var vm = this;

    this.registerEvents = function () {
        document.addEventListener('click', function (e) {
            targetElement = e.target; //target element
            classList = targetElement.classList;

            if (classList.contains('deleteSupplier')) {
                e.preventDefault(); //default mechanism

                sId = targetElement.dataset.sid;
                supplierName = targetElement.dataset.name;

                BootstrapDialog.confirm({
                    type: BootstrapDialog.TYPE_DANGER,
                    title: 'Delete Supplier',
                    message: 'Are you sure to delete <strong>' + supplierName + '</strong>?',
                    callback: function (isDelete) {
                        if (isDelete) {
                            $.ajax({
                                method: 'POST',
                                data: {
                                    id: sId,
                                    table: 'suppliers'
                                },
                                url: 'database/delete.php',
                                dataType: 'json',
                                success: function (data) {
                                    message = data.success ?
                                        supplierName + ' Successfully deleted!' : 'Error processing your request!';

                                    BootstrapDialog.alert({
                                        type: data.success ? BootstrapDialog.TYPE_SUCCESS : BootstrapDialog.TYPE_DANGER,
                                        message: message,
                                        callback: function () {
                                            if (data.success) location.reload();
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
            }

            if (classList.contains('updateSupplier')) {
                e.preventDefault(); //prevent from loading

                // getting the data
                var supplierName = targetElement.closest('tr').querySelector('td.supplier_name').innerHTML;
                var supplierLocation = targetElement.closest('tr').querySelector('td.supplier_location').innerHTML;
                var email = targetElement.closest('tr').querySelector('td.email').innerHTML;
                var supplierId = targetElement.dataset.sid;

                BootstrapDialog.confirm({
                    title: 'Update ' + supplierName,
                    message: '<form id="editProductForm">\
                        <div class="appFormInputContainer">\
                            <label for="supplier_name">Supplier Name</label>\
                            <input type="text" class="appFormInput" id="supplier_name" value="' + supplierName + '" placeholder="Enter Supplier name" name="supplier_name" />\
                        </div>\
                        <div class="appFormInputContainer">\
                            <label for="supplier_location">Supplier Location</label>\
                            <input type="text" class="appFormInput" id="supplier_location" value="' + supplierLocation + '" placeholder="Enter Supplier Location" name="supplier_location" />\
                        </div>\
                        <div class="appFormInputContainer">\
                            <label for="email">Email</label>\
                            <input class="appFormInput" type="text" id="email" value="' + email + '" placeholder="Enter email" name="email">\
                        </div>\
                    </form>',
                    callback: function (isUpdate) {
                        if (isUpdate) {
                            $.ajax({
                                method: 'POST',
                                data: {
                                    sId: supplierId,
                                    supplier_name: document.getElementById('supplier_name').value,
                                    supplier_location: document.getElementById('supplier_location').value,
                                    email: document.getElementById('email').value,
                                },
                                url: 'database/update_supplier.php',
                                dataType: 'json',
                                success: function (data) {
                                    if (data.success) {
                                        BootstrapDialog.alert({
                                            type: BootstrapDialog.TYPE_SUCCESS,
                                            message: data.message,
                                            callback: function () {
                                                location.reload();
                                            }
                                        });
                                    } else {
                                        BootstrapDialog.alert({
                                            type: BootstrapDialog.TYPE_DANGER,
                                            message: data.message,
                                        });
                                    }
                                }
                            });
                        }
                    }
                });
            }
        });
    };

    this.initialize = function () {
        this.registerEvents();
    };
}

var script = new script();
script.initialize();

</script> 
  

</body>
</html>


























