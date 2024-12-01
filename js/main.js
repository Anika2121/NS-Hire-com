let nav= document.querySelector(".navbar");
window.onscroll=function(){
    if(document.getElement.srollTop>20){
        nav.classList.add("header-scrolled");
    }
    else{
        nav.classList.remove("header-scrolled");
    }
}