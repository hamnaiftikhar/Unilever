<div class="dashboard_sidebar" id="dashboard_sidebar">
    <h3 class="dashboard_logo" id="dashboard_logo">Unilever</h3>
    <div class="dashboard_sidebar_user">
        <img src="images/user/naruto.jpg" alt="User image." id="userImage" />
        <span><?= $user['first_name'] . ' ' . $user['last_name'] ?></span>
    </div>
    <div class="dashboard_sidebar_menus">
        <ul class="dashboard_menu_lists">
            
            <!-- class="menuActive" -->
            
            <li>
                <a href="./dashboard.php"><i class="fa fa-dashboard"></i><span class="menuText">Dashboard</span></a>
            </li>
            <li>
                <a href="./user_add.php"><i class="fa fa-user-plus"></i><span class="menuText">Add User</span></a>
            </li>
            <li>
                <a href=""><i class="fa fa-money"></i><span class="menuText">REVENUE MANAGEMENT</span></a>
            </li>
            <li>
                <a href=""><i class="fa fa-cog"></i><span class="menuText">SETTINGS</span></a>
            </li>
            <li>
                <a href=""><i class="fa fa-line-chart"></i><span class="menuText">STATS</span></a>
            </li>                    
        </ul>
    </div>
</div>