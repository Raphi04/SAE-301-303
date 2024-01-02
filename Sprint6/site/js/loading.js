var image = document.getElementById("imageLoading");
var loading = document.getElementById("loading");
var container = document.getElementById("loader");

document.addEventListener("DOMContentLoaded", () =>{
    loading.style.display = "none";
    image.classList.add("dispawn1");
    setTimeout(() => {
        image.classList.remove("dispawn1");
        image.classList.add("dispawn2");
        container.classList.add("loaderDispawn");
    }, 1500);
    setTimeout(() => {container.classList.add("loaderDisplayNone")}, 2000); 
});
