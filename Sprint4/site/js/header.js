document.addEventListener("scroll", (event) => {
    var header = document.getElementById("main-navbar");
    console.log(window.scrollY);
    if (window.scrollY > 0) {
        header.style.backgroundColor = "#2a78eb";
    } else {
        header.style.backgroundColor = "transparent";
    }
});