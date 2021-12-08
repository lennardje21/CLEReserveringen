const header = document.querySelector("header")
const navbar = document.querySelector(".navbar")
const navButtons = document.querySelectorAll(".navbar-right")
const startPosition = navbar.offsetTop
const observer = new IntersectionObserver(entries => adjustVisibility(entries))

function init(){
    observer.observe(header)
}
function adjustVisibility(entries){
    const mobileview = window.matchMedia("(max-width: 550px;)");

    for (let entry of entries){
        if (!mobileview.matches){
            if (entry.target.nodeName === "HEADER"){
                if (entry.intersectionRatio > 0){
                    removeSelected();
                    navbar.classList.remove("sticky")
                } else{
                    navbar.classList.add("sticky")
                }
            }
        }
    }
}
function removeSelected(){
    for (let btn of navButtons){
        btn.classList.remove("selected");
    }
}

init()