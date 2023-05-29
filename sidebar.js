document.querySelector("#hamburger").addEventListener('click', toggleBar);

function toggleBar(event){
    document.querySelector("nav").classList.toggle("max");
}

window.addEventListener("resize", function() {
    if (window.innerWidth > 700) {
        document.querySelector("nav").classList.remove("max");
    }
});