/* Когда пользователь нажимает на кнопку,
переключение между скрытием и отображением раскрывающегося содержимого */
function menuBtnClick() {
    
    let btnMenu = document.querySelector('#myDropdown');
    //btnMenu.classList.toggle("show");
    if (btnMenu.classList.contains('show')) {
        btnMenu.classList.remove('show');
    } else {
        btnMenu.classList.toggle("show");
    }
}
// Закройте выпадающее меню, если пользователь щелкает за его пределами
window.onclick = function (event) {
    if (!event.target.matches('.barbtn')) {
        let btnMenu = document.querySelector('#myDropdown');
        if (btnMenu.classList.contains('show')) {
            btnMenu.classList.remove('show');
        }
    }
}

function showPassword(name) {
    var x = document.getElementsByName (name);
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}

document.querySelector('#btnLink').addEventListener('click', menuBtnClick);