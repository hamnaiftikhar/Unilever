<?php

    session_start();

    if(!isset($_SESSION['users'])) {
        header('location: login.php');
    }
    $_SESSION['table'] = 'user';
    $user = $_SESSION['users'];

    $show_table = 'user';
    $users = include('database/show.php');     
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Users - Inventory Management System</title>
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
                            <h1 class="section_header"><i class="fa fa-list"></i> List of Users</h1>
                            <div class="section_content">
                                <div class="users">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($users as $index => $user){ ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td class="firstName"><?= $user['first_name'] ?></td>
                                                    <td class="lastName"><?= $user['last_name'] ?></td>
                                                    <td class="email"><?= $user['email'] ?></td>
                                                    <td><?= date('M d,Y @ h:i:s A', strtotime($user['created_at'])) ?></td>
                                                    <td><?= date('M d,Y @ h:i:s A', strtotime($user['updated_at'])) ?></td>
                                                    <td>
                                                        <a href="" class="updateUser" data-userid="<?= $user['id'] ?>"><i class="fa fa-pencil" ></i> Edit </a>
                                                        <a href="" class="deleteUser" data-userid="<?= $user['id'] ?>" data-fname="<?= $user['first_name'] ?>" data-lname="<?= $user['last_name'] ?>" ><i class="fa fa-trash"></i> Delete </a>
                                                        
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <p class="userCount"><?= count($users) ?> Users </p>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include('partials/app-scripts.php'); ?>

<script>
    function script(){
        
        
        this.initialize = function(){
            this.registerEvents();
        },
            
        this.registerEvents = function(){
            document.addEventListener('click', function(e){
                targetElement = e.target;
                classList = targetElement.classList; 
                
                if(classList.contains('deleteUser')){
                    e.preventDefault();
                    userId = targetElement.dataset.userid;
                    fname = targetElement.dataset.fname;
                    lname = targetElement.dataset.lname;
                    fullName = fname + ' ' + lname;
                    
                    BootstrapDialog.confirm({
                        title: 'Delete User',
                        type: BootstrapDialog.TYPE_DANGER,
                        message: 'Are you sure to delete <strong>'+ fullName +'</strong> ?',
                        callback: function(isDelete){
                            if(isDelete){
                                $.ajax({
                                    method: 'POST',
                                    data: {

                                        id: userId,
                                        table: 'user'

                                    },
                                    url: 'database/delete.php',
                                    dataType : 'json',
                                    success: function(data){
                                        message = data.success ?
                                            fullName + 'Successfully deleted!' : 'Error processing your request!';

                                        BootstrapDialog.alert({
                                            type: data.success ? BootstrapDialog.TYPE_SUCCESS : BootstrapDialog.TYPE_DANGER,
                                            message: message,
                                            callback: function() {
                                                if(data.success) location.reload();
                                            }
                                        });
                                    }
                                });
                            }
                            
                        }
                    });
                }
                
                if(classList.contains('updateUser')){
                    e.preventDefault(); //prevent from loading
                    
                    // getting the data
                    
                    firstName = targetElement.closest('tr').querySelector('td.firstName').innerHTML;
                    lastName = targetElement.closest('tr').querySelector('td.lastName').innerHTML;
                    email = targetElement.closest('tr').querySelector('td.email').innerHTML;
                    userId = targetElement.dataset.userid;
                    
                    BootstrapDialog.confirm({
                        title: 'Update ' + firstName + ' ' + lastName,
                        message: '<form>\
                            <div class="form-group">\
                                <label for="firstName">First Name:</label>\
                                <input type="text" class="form-control" id="firstName" value="'+ firstName +'">\
                            </div>\
                            <div class="form-group">\
                                <label for="lastName">Last Name:</label>\
                                <input type="text" class="form-control" id="lastName" value="'+ lastName +'">\
                            </div>\
                            <div class="form-group">\
                                <label for="email">Email address:</label>\
                                <input type="email" class="form-control" id="emailUpdate" value="'+ email +'">\
                            </div>\
                        </form>',
                        callback: function(isUpdate) {
                            if(isUpdate) { // updates if user click updates button
                                $.ajax({
                                    method: 'POST',
                                    data: {
                                        userId: userId,
                                        f_name: document.getElementById('firstName').value,
                                        l_name: document.getElementById('lastName').value,
                                        email: document.getElementById('emailUpdate').value,
                                    },
                                    url: 'database/update-user.php',
                                    dataType : 'json',
                                    success: function(data) {
                                        if (data.success) {
                                            BootstrapDialog.alert({
                                                type: BootstrapDialog.TYPE_SUCCESS,
                                                message: data.message,
                                                callback: function() {
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
        }
    }
    
    var script = new script;
    script.initialize();
    
    
</script>
</body>
</html>