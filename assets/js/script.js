let navOptions = document.getElementsByClassName("nav--options");
let explore = document.getElementsByClassName("explore");
let sidebarIcon = document.querySelector(".sidebar--icon");
let sidebar = document.querySelector(".sidebar"); 
var closeIcon = document.querySelector(".close--icon");
var arrayDown = document.querySelector(".arrow--down");
var sidebarOptions = document.querySelector(".sidenav--options");

explore[0].addEventListener("click", () => {

    if(navOptions[0].style.display == "flex") {
        navOptions[0].style.display = "none";
    } else {
        navOptions[0].style.display = "flex";
    }
})

arrayDown.addEventListener("click", () => {
    
    if(sidebarOptions.style.display == "flex") {
        sidebarOptions.style.display = "none";
        arrayDown.style.transform = "rotate(0deg)";
    } else {
        sidebarOptions.style.display = "flex";
        arrayDown.style.transform = "rotate(180deg)";
    }
})

sidebarIcon.addEventListener("click", () => {
    sidebar.style.display = "flex";
})

closeIcon.addEventListener("click", () => {
    sidebar.style.display = "none";
})

window.addEventListener("resize", () => {
    if(window.innerWidth > 700) {
        sidebar.style.display = "none";
    }
});