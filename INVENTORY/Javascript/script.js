var sidebarIsOpen = true;

toggle_btn.addEventListener( 'click', (event) => {
    event.preventDefault();

    if(sidebarIsOpen){
        dashboard_sidebar.style.width = "15%";
        dashboard_sidebar.style.transition = '0.3s all';
        dashboard_content_container.style.width = "90%";
        dashboard_logo.style.fontSize = "35px";
        userImage.style.width = "50px";

        menuIcons = document.getElementsByClassName('menuText');
        for(var i = 0; i < menuIcons.length; i++){
            menuIcons[i].style.display = 'none';
        }

        document.getElementsByClassName('dashboard_menu_lists')[0].style.textAlign = 'center';
        sidebarIsOpen = false;    

    }

    else{

        dashboard_sidebar.style.width = "20%";
        dashboard_content_container.style.width = "80%";
        dashboard_logo.style.fontSize = "70px";
        userImage.style.width = "80px";

        menuIcons = document.getElementsByClassName('menuText');
        for(var i = 0; i < menuIcons.length; i++){
            menuIcons[i].style.display = 'inline-block';
        }

        document.getElementsByClassName('dashboard_menu_lists')[0].style.textAlign = 'left'; 
        sidebarIsOpen = true;
    }

});

// Submenu show / hide function
document.addEventListener('click', function(e) {
    let clickedEl = e.target;

    if (clickedEl.classList.contains('showHideSubMenu')) {
        e.preventDefault(); // Prevent default link behavior
                
        let subMenu = clickedEl.closest('li').querySelector('.subMenus');
        let mainMenuIcon = clickedEl.closest('li').querySelector('.mainMenuIconArrow');
        
        
        //close all submenus
        
        
        let subMenus = document.querySelectorAll('.subMenus');
        subMenus.forEach((sub) => {
            if(subMenu !== sub) sub.style.display = 'none';
        });
        
        
        // call func to hide/show submenu
        showHideSubMenu(subMenu, mainMenuIcon);


        //console.log(targetMenu);
    }
});

// function - to show/hide submenu

function showHideSubMenu(subMenu, mainMenuIcon){
            // check if there is a submenu
    if (subMenu != null) {

        if(subMenu.style.display === 'block'){
            subMenu.style.display = 'none';
            mainMenuIcon.classList.remove('fa-angle-down');
            mainMenuIcon.classList.add('fa-angle-left');
        } else{
            subMenu.style.display = 'block';
            mainMenuIcon.classList.remove('fa-angle-left');
            mainMenuIcon.classList.add('fa-angle-down');
        }

    }
}


// add or hide active class to menu 
// get the current page
// use selector to get the current menu or submenu
// add active class


let pathArray = window.location.pathname.split('/');
let curFile = pathArray[pathArray.length - 1];

let curNav = document.querySelector('a[href="./'+ curFile +'"]');
//curNav.classList.add('subMenuActive');

let mainNav = curNav.closest('li.liMainMenu');
mainNav.style.background = '#ED8DBA';

let subMenu = curNav.closest('.subMenus');
let mainMenuIcon = mainNav.querySelector('i.mainMenuIconArrow');


// Call function to hide/show submenu
showHideSubMenu(subMenu, mainMenuIcon);
