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
    <title>View Purchase Orders - Inventory Management System</title>
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
                            <h1 class="section_header"><i class="fa fa-list"></i> List of PO</h1>
                            <div class="section_content">
                                <div class="poListContainers">
                                    <?php 
    
                                        $stmt = $conn->prepare("
                                        SELECT order_product.id, products.machine_name, order_product.quantity_ordered, user.first_name, order_product.batch, order_product.quantity_received, user.last_name, suppliers.supplier_name, order_product.status, order_product.created_at 
                                            FROM order_product, suppliers, products, user
                                            WHERE
                                                order_product.supplier = suppliers.id
                                                    AND
                                                order_product.product = products.id
                                                    AND
                                                order_product.created_by = user.id
                                            ORDER BY
                                                order_product.created_at DESC
                                            ");
                                        $stmt->execute();
                                        $purchase_orders = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                                        
                                        $data = [];
                                        foreach($purchase_orders as $purchase_order){
                                            $data[$purchase_order['batch']][] = $purchase_order;
                                        }     
                                    ?>
                                    
                                    <?php
                                        foreach($data as $batch_id => $batch_pos){
                                        
                                    
                                    ?>
                                    
                                    <div class="poList" id="container-<?= $batch_id ?>">
                                        <p>Batch #: <?= $batch_id ?> </p>
                                        <table>
                                            <thread>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product</th>
                                                    <th>Quantity Ordered</th>
                                                    <th>Quantity Received</th>
                                                    <th>Supplier</th>
                                                    <th>Status</th>
                                                    <th>Ordered by</th>
                                                    <th>Created Date</th>
                                                </tr>
                                            </thread>
                                            <tbody>
                                                <?php
                                                    foreach($batch_pos as $index => $batch_po){
                                                ?>
                                                <tr>
                                                    <td> <?= $index +1 ?> </td>
                                                    <td class="po_product"><?= $batch_po['machine_name'] ?></td>
                                                    <td class="po_qty_ordered"><?= $batch_po['quantity_ordered'] ?></td>
                                                    <td class="po_qty_received"><?= $batch_po['quantity_received'] ?></td>
                                                    <td class="po_qty_supplier"><?= $batch_po['supplier_name'] ?></td>
                                                    <td class="po_qty_status"><span class="po-badge po-badge-<?= $batch_po['status'] ?>"><?= $batch_po['status'] ?></span></td>
                                                    <td><?= $batch_po['first_name'] . ' ' . $batch_po['last_name'] ?></td>
                                                    <td>
                                                        <?= $batch_po['created_at'] ?>
                                                        <input type="hidden" class="po_qty_row_id" value="<?= $batch_po['id'] ?>">
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <div class="poOrderUpdateBtnContainer alignRight">
                                            <button class="appBtn updatePoBtn" data-id="<?= $batch_id ?>">Update</button>
                                        </div>
                                    </div>
                                    <?php } ?>
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

                if (classList.contains('updatePoBtn')) {
                    e.preventDefault(); //default mechanism
                    
                    batchNumber = targetElement.dataset.id;
                    
                    batchNumberContainer = 'container-' + batchNumber;
                    
                    // get all purchased orderered product records
                    
                    productList = document.querySelectorAll('#' + batchNumberContainer + ' .po_product');
                    qtyOrderedList = document.querySelectorAll('#' + batchNumberContainer + ' .po_qty_ordered');
                    qtyReceivedList = document.querySelectorAll('#' + batchNumberContainer + ' .po_qty_received');
                    supplierList = document.querySelectorAll('#' + batchNumberContainer + ' .po_qty_supplier');
                    statusList = document.querySelectorAll('#' + batchNumberContainer + ' .po_qty_status');
                    rowIds = document.querySelectorAll('#' + batchNumberContainer + ' .po_qty_row_id');
                    
                    
                    //loop thru
                    
                    poListsArr = [];
                    
                    for(i=0;i<productList.length;i++){
                       poListsArr.push({
                           name: productList[i].innerText,
                           qtyOrdered: qtyOrderedList[i].innerText,
                           qtyReceived: qtyReceivedList[i].innerText,
                           supplier: supplierList[i].innerText,
                           status: statusList[i].innerText,
                           id: rowIds[i].value
                       }); 
                    }
                    
                    
                    // Store in HTML
                    var poListHtml = '\
                        <table id="formTable_'+ batchNumber +'">\
                            <thead>\
                                <tr>\
                                    <th>Machine Name</th>\
                                    <th>Quantity Ordered</th>\
                                    <th>Quantity Received</th>\
                                    <th>Supplier</th>\
                                    <th>Status</th>\
                                </tr>\
                            </thead>\
                            <tbody>';

                    poListsArr.forEach((poList) => {
                        poListHtml += '\
                            <tr>\
                                <td class="po_product alignLeft">' + poList.name + '</td>\
                                <td class="po_qty_ordered">' + poList.qtyOrdered + '</td>\
                                <td class="po_qty_received"><input type="number" value="' + poList.qtyReceived + '" /></td>\
                                <td class="po_qty_supplier alignLeft">' + poList.supplier + '</td>\
                                <td>\
                                    <select class="po_qty_status">\
                                        <option value="pending"' + (poList.status == 'pending' ? ' selected' : '') + '>pending</option>\
                                        <option value="incomplete"' + (poList.status == 'incomplete' ? ' selected' : '') + '>incomplete</option>\
                                        <option value="complete"' + (poList.status == 'complete' ? ' selected' : '') + '>complete</option>\
                                    </select>\
                                    <input type="hidden" class="po_qty_row_id" value="' + poList.id + '">\
                                </td>\
                            </tr>';
                    });

                    poListHtml += '</tbody></table>';
        
                    // Display Update PO
                    pName = targetElement.dataset.name;

                    BootstrapDialog.confirm({
                        type: BootstrapDialog.TYPE_PRIMARY,
                        title: 'Update Purchase Order: Batch #: <strong>'+ batchNumber +'</strong>',
                        message: poListHtml,
                        callback: function (toAdd) {
                            // if we add
                            if(toAdd){
                                
                                formTableContainer = 'formTable_' + batchNumber;
                                
                                // get all purchase orders records
                                
                                qtyReceivedList = document.querySelectorAll('#' + formTableContainer + ' .po_qty_received input');
                                statusList = document.querySelectorAll('#' + formTableContainer + ' .po_qty_status');
                                rowIds = document.querySelectorAll('#' + formTableContainer + ' .po_qty_row_id');
                                qtyOrdered = document.querySelectorAll('#' + formTableContainer + ' .po_qty_ordered');


                                //loop thru

                                poListsArrForm = [];

                                for(i=0;i<productList.length;i++){
                                   poListsArrForm.push({
                                       qtyReceived: qtyReceivedList[i].value,
                                       status: statusList[i].value,
                                       id: rowIds[i].value,
                                       qtyOrdered: qtyOrdered[i].innerText
                                   }); 
                                }
                                
                                //add a request to capture data to be displayed
                                
                                
                                
                                $.ajax({
                                    method: 'POST',
                                    data: {
                                        payload: poListsArrForm
                                    },
                                    url: 'database/update_order.php',
                                    dataType: 'json',
                                    success: function (data) {
                                        message = data.message;

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
            });
        
        },

        this.initialize = function () {
            this.registerEvents();
        }
    }

    var script = new script();
    script.initialize();

</script> 
  

</body>
</html>


























