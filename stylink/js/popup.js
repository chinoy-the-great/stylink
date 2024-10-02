

// Wait for the DOM to load
document.addEventListener('DOMContentLoaded', (event) => {
    const popups = document.querySelectorAll('.message-popup');

    popups.forEach(popup => {
        popup.classList.add('show'); // Show the popup
        setTimeout(() => {
            popup.classList.remove('show'); // Hide after a few seconds
        }, 5000); // 3000 milliseconds (3 seconds)
    });
});