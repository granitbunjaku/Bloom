var slides = document.getElementsByClassName("slideItem");
var size = slides.length-1;
var index = 0;

function slide (n) {
    slides[index].style.display = "none";

    if((index+n) == -1) {
        index = size;
    } else if((index+n) > size){
        index = 0;
    } else {
        index = index + n;
    }

    slides[index].style.display = "flex";
}