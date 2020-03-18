import './plugins/popup';
const burgerNav = document.getElementById("burger_nav");
const burgerIco = document.getElementById("rotation");

let nav = document.getElementById("a_list");

let navChildren: HTMLCollectionOf<HTMLAnchorElement>|null  = null;
if(nav !== null){
    navChildren = nav.getElementsByTagName("a");
}

let isClose = true;

function closeNav(): void
{
    if(burgerNav !== null){
        burgerNav.style.width = "0%";
        isClose = true;
    }
}

function rotate(): void
{
    if(burgerIco !== null) {
        burgerIco.classList.toggle("change")
    }
}

function burger(): void
{
    if(burgerNav !== null) {
        if(isClose){
            burgerNav.style.width = "100%";
            isClose = false;
        }else{
            burgerNav.style.width = "0%";
            isClose = true;
        }
    }
}

if(navChildren !==null){
    for(let i = 0; i < navChildren.length; i++) {
        navChildren[i].addEventListener('click', function () {
            closeNav();
            rotate();
        });
    }
}

if(burgerIco !== null) {
    burgerIco.addEventListener('click', function () {
        burger();
        rotate();
    });
}

const flash: HTMLElement|null = document.getElementById("alert");
const buttonFlash: HTMLElement|null = document.getElementById("removeButton");
if(buttonFlash !== null) {
    buttonFlash.addEventListener('click', function () {
        if(flash !== null) {
            flash.style.display = "none";
        }
    });
}

