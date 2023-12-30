document.addEventListener("scroll", (event) => {
    var header = document.getElementById("main-navbar");
    console.log(window.scrollY);
    if (window.scrollY > 0) {
        header.style.backgroundColor = "#2a78eb";
    } else {
        header.style.backgroundColor = "transparent";
    }
});

function decouverte() {
    let activities = document.getElementById("activities");
    activities.scrollIntoView({behavior: "smooth", block: "start", inline: "nearest" });
}

function responsiveMenu() {
    let navBar = document.getElementById("main-navbar");
    navBar.style.backgroundColor = "rgb(42, 120, 235)";
}