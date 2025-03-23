
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(() => {
        document.querySelector(".loader").classList.add("hidden"); // Hide loader
        document.getElementById("content").classList.add("show"); // Show content
    }, 1000); // Delay ensures the div is fully loaded
});
