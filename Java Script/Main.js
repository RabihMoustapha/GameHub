function teleport() {
    var intro = alert("- Home \n - About \n - Service \n - Contact \n PAGE ONLY");
    var teleport = prompt("Enter a page to teleport");
    teleport = teleport.toLowerCase();
    while (teleport != "home" && teleport != "service" && teleport != "about" && teleport != "contact") {
        intro = alert("- Home \n - About \n - Service \n - Contact \n PAGE ONLY");
        teleport = prompt("Enter a page to teleport");
        teleport = teleport.toLowerCase();
    }
    switch (teleport) {
        case 'home' :
            window.location.href = "Home.php";
            break;
        case 'service' :
            window.location.href = "Service.php";
            break;
        case 'about' :
            window.location.href = "About.php";
            break;
        case 'contact' :
            window.location.href = "Contact.php";
            break;
    }
}