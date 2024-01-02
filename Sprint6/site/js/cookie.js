var cookieContainer = document.getElementById("cookieContainer");

function cookiePopUpClose() {
    cookieContainer.classList.add("cookieContainerNone");
}

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 365));
    let expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    cookieContainer.classList.add("cookieContainerNone");
  }
  
  function checkCookie(cookie) {
    let cookieList = document.cookie.split(";");
    for (let i=0; i < cookieList.length; i++){
        let cookieNameAndValue = cookieList[i].split("=");
        let cookieName = cookieNameAndValue[0];
        if(cookie == cookieName) {
            cookieContainer.classList.add("cookieContainerNone");
        }
    }
  }
  checkCookie("cookie");