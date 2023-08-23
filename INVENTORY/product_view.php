<?php
    session_start();

    if(!isset($_SESSION['users'])) {
        header('location: login.php');
    }

    // get all products 

    $show_table = 'products';
    $products = include('database/show.php');    

    // Search functionality
    $machineNameQuery = isset($_GET['machine_name']) ? $_GET['machine_name'] : '';
    $descriptionQuery = isset($_GET['description']) ? $_GET['description'] : '';

    if (!empty($machineNameQuery) || !empty($descriptionQuery)) {
        $filteredProducts = array_filter($products, function ($product) use ($machineNameQuery, $descriptionQuery) {
            return (
                (empty($machineNameQuery) || strpos(strtolower($product['machine_name']), strtolower($machineNameQuery)) !== false) &&
                (empty($descriptionQuery) || strpos(strtolower($product['description']), strtolower($descriptionQuery)) !== false)
            );
        });
        $products = $filteredProducts;
    }
?>



<!DOCTYPE html>
<html>
<head>
    <title>View Products - Inventory Management System</title>
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
                            <h1 class="section_header"><i class="fa fa-list"></i> List of Products</h1>
                            <div class="section_content">
                                <div class="users">
                                    <div class="section_header_search">
                                        <form id="searchForm" method="get">
                                            <div class="searchInputContainer">
                                                <label for="machineNameInput" class="searchBar">Search Machine Part Location:</label>
                                                <input type="text" id="machineNameInput" name="machine_name" value="<?= isset($machineNameQuery) ? $machineNameQuery : ''; ?>" placeholder="Search by machine Name">
                                            </div>
                                            <div class="searchInputContainer">
                                                <input type="text" id="descriptionInput" name="description" value="<?= isset($descriptionQuery) ? $descriptionQuery : ''; ?>" placeholder="Search by Description">
                                            </div>
                                            <button type="submit">Go</button>
                                        </form>
                                    </div>

                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Machine Name</th>
                                                <th>Description</th>
                                                <th>Location</th>
                                                <th>Suppliers</th>
                                                <th>Created By</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($products as $index => $product){ ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td class="machine_name"><?= $product['machine_name'] ?></td>
                                                    <td class="description"><?= $product['description'] ?></td>
                                                    <td class="location"><?= $product['location'] ?></td>
                                                    <td class="supplier_name">
                                                        <?php
                                                            $supplier_list = '-';
                                                            $pid = $product['id'];
                                                            $stmt = $conn->prepare("
                                                            SELECT supplier_name
                                                                FROM suppliers, productsuppliers 
                                                            Where 
                                                                productsuppliers.product=$pid
                                                                    AND 
                                                                productsuppliers.supplier= suppliers.id
                                                                
                                                                ");
                                                            $stmt->execute();
                                                            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                                                           
                                                            if($row){
                                                                $supplier_arr = array_column($row,'supplier_name');
                                                                $supplier_list = '<li>' .  implode("</li><li>", $supplier_arr);
                                                            }                               
                                                                                           
                                                            
                                                            echo $supplier_list;                             
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $uid = $product['created_by'];
                                                            $stmt = $conn->prepare("SELECT * FROM user Where id=$uid");
                                                            $stmt->execute();
                                                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            $created_by_name = $row['first_name'] . '' . $row['last_name'];
                                                            echo $created_by_name;
                                                                                           
                                                        ?>
                                                    </td>
                                                    <td><?= date('M d,Y @ h:i:s A', strtotime($product['created_at'])) ?></td>
                                                    <td><?= date('M d,Y @ h:i:s A', strtotime($product['updated_at'])) ?></td>
                                                    <td>
                                                        <a href="" class="updateProduct" data-pid="<?= $product['id'] ?>"><i class="fa fa-pencil"></i> Edit</a>
                                                        <a href="" class="deleteProduct" data-name="<?= $product['machine_name'] ?>" data-pid="<?= $product['id'] ?>"><i class="fa fa-trash"></i> Delete</a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <p class="userCount"><?= count($products) ?> products </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   
    
    <?php include('partials/app-scripts.php'); ?>
    
<!-- JScript Code For searching Products-->
<script>
    const searchForm = document.getElementById('searchForm');
    const machineNameInput = document.getElementById('machineNameInput');
    const descriptionInput = document.getElementById('descriptionInput');

    searchForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const machineNameQuery = machineNameInput.value.trim();
        const descriptionQuery = descriptionInput.value.trim();
        const queryString = `machine_name=${encodeURIComponent(machineNameQuery)}&description=${encodeURIComponent(descriptionQuery)}`;
        window.location.href = `product_view.php?${queryString}`;
    });
</script>


    
 <!-- JScript Code For Updating / Deleting Products-->   
    
<script>
function script() {
    var vm = this;

    this.registerEvents = function () {
        document.addEventListener('click', function (e) {
            targetElement = e.target; //target element
            classList = targetElement.classList;

            if (classList.contains('deleteProduct')) {
                e.preventDefault(); //default mechanism

                pId = targetElement.dataset.pid;
                pName = targetElement.dataset.name;

                BootstrapDialog.confirm({
                    type: BootstrapDialog.TYPE_DANGER,
                    title: 'Delete Product',
                    message: 'Are you sure to delete <strong>' + pName + '</strong>?',
                    callback: function (isDelete) {
                        if (isDelete) {
                            $.ajax({
                                method: 'POST',
                                data: {
                                    id: pId,
                                    table: 'products'
                                },
                                url: 'database/delete.php',
                                dataType: 'json',
                                success: function (data) {
                                    message = data.success ?
                                        pName + ' Successfully deleted!' : 'Error processing your request!';

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

            if (classList.contains('updateProduct')) {
                e.preventDefault(); //prevent from loading

                // getting the data
                var machineName = targetElement.closest('tr').querySelector('td.machine_name').innerHTML;
                var description = targetElement.closest('tr').querySelector('td.description').innerHTML;
                var loc = targetElement.closest('tr').querySelector('td.location').innerHTML;
                var productId = targetElement.dataset.pid;

                BootstrapDialog.confirm({
                    title: 'Update ' + machineName,
                    message: '<form id="editProductForm">\
                        <div class="appFormInputContainer">\
                            <label for="machine_name">Machine Name</label>\
                            <input type="text" class="appFormInput" id="machine_name" value="' + machineName + '" placeholder="Enter product name" name="machine_name" />\
                        </div>\
                        <div class="appFormInputContainer">\
                            <label for="description">Description</label>\
                            <textarea class="appFormInput productTextAreaInput" id="description" placeholder="Enter Product Description" name="description">' + description + '</textarea>\
                        </div>\
                        <div class="appFormInputContainer">\
                            <label for="location">Product Location</label>\
                            <input class="appFormInput" type="text" id="location" value="' + loc + '" placeholder="Enter Product Location" name="location">\
                        </div>\
                    </form>',
                    callback: function (isUpdate) {
                        if (isUpdate) {
                            $.ajax({
                                method: 'POST',
                                data: {
                                    pId: productId,
                                    machine_name: document.getElementById('machine_name').value,
                                    description: document.getElementById('description').value,
                                    location: document.getElementById('location').value,
                                },
                                url: 'database/update_product.php',
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


























