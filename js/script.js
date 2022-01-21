/* deze functie zorgt ervoor dat de navigatie in mobile versie kan in en uit klappen */

const primaryNav = document.querySelector('.primary-navigation');
const navToggle = document.querySelector('.mobile-nav-toggle');

navToggle.addEventListener('click', () => {
    const visibility = primaryNav.getAttribute('data-visible');

    if (visibility === "false"){
        primaryNav.setAttribute('data-visible', true)
        navToggle.setAttribute('aria-expanded', true)
    } else {
        primaryNav.setAttribute('data-visible', false)
        navToggle.setAttribute('aria-expanded', false)
    }
})

/* met deze code wil ik het actieve onderdeel van de navbar laten zien */

const primaryNavActive = document.getElementById('primary-navigation');
const links = primaryNavActive.getElementsByClassName('link');

for (let i = 0; i < links.length; i++) {
    links[i].addEventListener("click", function() {
        let current = document.getElementsByClassName("active");
        current[0].className = current[0].className.replace(" active", "");
        this.className += " active";
    });
}

/* Functie om de register modalbox te laten zien */
const modal = document.getElementById("PlsModal");

const btn = document.getElementById("openModal");

btn.onclick = function(){
    modal.style.display = "block";
};

window.onclick = function(event) {
    if (event.target === modal){
        modal.style.display = "none";
    }
};

function showInfo(id, uniqueCode) {
    alert(`Uw reservering is succesvol geplaatst\nSla deze codes op zodat u later wijzigingen kan doorvoeren.\nid = ${id}\ncode = ${uniqueCode}`)
}
