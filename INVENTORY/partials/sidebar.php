<?php 
    $user = $_SESSION['users'];

?>



<div class="dashboard_sidebar" id="dashboard_sidebar">
    <h3 class="dashboard_logo" id="dashboard_logo">Unilever</h3>
    <div class="dashboard_sidebar_user">
        <img src="images/user/naruto.jpg" alt="User image." id="userImage" />
        <span><?= $user['first_name'] . ' ' . $user['last_name'] ?></span>
    </div>
    <div class="dashboard_sidebar_menus">
        <ul class="dashboard_menu_lists">
            
            <!-- class="menuActive" -->
            
            <li class="liMainMenu">
                <a href="./dashboard.php"><i class="fa fa-dashboard"></i><span class="menuText">Dashboard</span></a>
            </li>
            <li class="liMainMenu"> 
                <a href="javascript:void(0);" class="showHideSubMenu">  <!-- SUbmenus  LIST -->
                <i class="fa fa-product-hunt showHideSubMenu"></i>
                <span class="menuText showHideSubMenu">Product</span>
                <i class="fa fa-angle-left mainMenuIconArrow showHideSubMenu"></i>
                </a>
                
                <ul class="subMenus">
                    <li><a class="subMenuLink" href="./product_view.php"><i class="fa fa-circle-o"></i>View Product</a></li>
                    <li><a class="subMenuLink" href="./product_add.php"><i class="fa fa-circle-o"></i>Add Product</a></li>

                </ul>
            </li>
            <li  class="liMainMenu" >
                <a href="javascript:void(0);" class="showHideSubMenu">  <!-- SUbmenus  LIST -->
                <i class="fa fa-industry showHideSubMenu"></i>
                <span class="menuText showHideSubMenu">Supplier</span>
                <i class="fa fa-angle-left mainMenuIconArrow showHideSubMenu"></i>
                </a>
                
                <ul class="subMenus">
                    <li><a class="subMenuLink" href="./supplier_view.php"><i class="fa fa-circle-o"></i>View Supplier</a></li>
                    <li><a class="subMenuLink" href="./supplier_add.php"><i class="fa fa-circle-o"></i>Add Supplier</a></li>
                </ul>
            </li>
            <li  class="liMainMenu" >
                <a href="javascript:void(0);" class="showHideSubMenu">  <!-- SUbmenus  LIST -->
                <i class="fa fa-first-order showHideSubMenu"></i>
                <span class="menuText showHideSubMenu">Purchase Order</span>
                <i class="fa fa-angle-left mainMenuIconArrow showHideSubMenu"></i>
                </a>
                
                <ul class="subMenus">
                    <li><a class="subMenuLink" href="./product_order.php"><i class="fa fa-circle-o"></i>Add New PO</a></li>
                    <li><a class="subMenuLink" href="./view_order.php"><i class="fa fa-circle-o"></i>View Order</a></li>
                </ul>
            </li>
            
            <li class="liMainMenu showHideSubMenu">
                <a href="javascript:void(0);" class="showHideSubMenu">  <!-- SUbmenus  LIST -->
                    <i class="fa fa-user-plus showHideSubMenu"></i>
                    <span class="menuText showHideSubMenu">Users</span>
                    <i class="fa fa-angle-left mainMenuIconArrow showHideSubMenu"></i>
                </a>
                
                <ul class="subMenus">
                    <li><a class="subMenuLink" href="./user_view.php"><i class="fa fa-circle-o"></i>View Users</a></li>
                    <li><a class="subMenuLink" href="./user_add.php"><i class="fa fa-circle-o"></i>Add Users</a></li>
                </ul>
            </li>
<!--
            <li class="liMainMenu">
                <a href=""><i class="fa fa-file"></i><span class="menuText">Reports</span></a>
            </li>
           
            <li class="liMainMenu">
                <a href=""><i class="fa fa-line-chart"></i><span class="menuText">STATS</span></a>
            </li>                  
-->
        </ul>
    </div>
</div>
