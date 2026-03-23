function showMenu(){
    
    const menuLateral = document.getElementById('menu-lateral');
    const iconMenu = document.getElementById('img-menu');

    menuLateral.classList.toggle('ativa');
    if(menuLateral.classList.contains('ativa')){
        iconMenu.src = "img/icon-close-menu.png";
    } else {
        iconMenu.src = "img/icon-hamburger-menu.png";
    }
}