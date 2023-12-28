function changeColor(member) {
    let container = document.querySelector("#" + member);
    let circle = document.querySelector("#" + member + " .circle");
    let name = document.querySelector("#" + member + " .members-name");
    let role = document.querySelector("#" + member + " .members-role");
    let hr = document.querySelector("#" + member + " hr");
    let phone = document.querySelector("#" + member + " .members-phone");
    let mail = document.querySelector("#" + member + " .mailLogo img");
    let facebook = document.querySelector("#" + member + " .facebookLogo img");
    let linkedIn = document.querySelector("#" + member + " .linkedinLogo img");

    container.classList.toggle("memberHovered");
    circle.classList.toggle("memberHovered");
    name.classList.toggle("memberHovered");
    role.classList.toggle("memberHovered");
    hr.classList.toggle("memberHovered");
    phone.classList.toggle("memberHovered");

    if (mail.classList.contains("white")) {
        mail.src = "images/Letter.png";
        facebook.src = "images/Facebook.png";
        linkedIn.src = "images/LinkedIn.png";
        mail.classList.toggle("white");
        facebook.classList.toggle("white");
        linkedIn.classList.toggle("white");
    } else {
        mail.src = "images/LetterB.png";
        facebook.src = "images/FacebookB.png";
        linkedIn.src = "images/LinkedInB.png";
        mail.classList.toggle("white");
        facebook.classList.toggle("white");
        linkedIn.classList.toggle("white");
    }
}