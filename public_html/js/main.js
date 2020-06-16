function navigacija() {
    var meni = document.getElementById("myTopnav");
    if (meni.className === "topnav") {
        meni.className += " responsive";
    } else {
        meni.className = "topnav";
    }
}