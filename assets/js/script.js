let navOptions = document.getElementsByClassName("nav--options");
let explore = document.getElementsByClassName("explore");
let sidebarIcon = document.querySelector(".sidebar--icon");
let sidebar = document.querySelector(".sidebar"); 
var closeIcon = document.querySelector(".close--icon");
var arrayDown = document.querySelector(".arrow--down");
var arrayDown2 = document.querySelector(".arrow--down2");
var sidebarOptions = document.querySelector(".sidenav--options");

explore[0].addEventListener("click", (e) => {
    e.stopPropagation();
    if(navOptions[0].style.display == "flex") {
        arrayDown2.style.transform = "rotate(0deg)";
        navOptions[0].style.display = "none";
    } else {
        arrayDown2.style.transform = "rotate(180deg)";
        navOptions[0].style.display = "flex";
    }
})

Array.from(navOptions).forEach(element => {
    element.addEventListener("click", (e) => {
        e.stopPropagation();
    })
});

document.body.addEventListener("click", (e) => {
    arrayDown2.style.transform = "rotate(0deg)";
    navOptions[0].style.display = "none";
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
    document.body.style.overflowY = "hidden";
    sidebar.style.display = "flex";
})

closeIcon.addEventListener("click", () => {
    document.body.style.overflowY = "scroll";
    sidebar.style.display = "none";
})

window.addEventListener("resize", () => {
    if(window.innerWidth > 700) {
        sidebar.style.display = "none";
    }
});