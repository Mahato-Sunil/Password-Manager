document.addEventListener('DOMContentLoaded', () => {
    const switchTab = (cityName) => {
        let i;
        let x = document.getElementsByClassName("tabs");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        document.getElementById(newTab).style.display = "block";
    }
});