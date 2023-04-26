
/**
 * Check cred function
 */
const checkbox = document.getElementById('f_cred');

const box = document.getElementById('show_cred');

checkbox.addEventListener('click', function handleReady() {
    if (checkbox.checked) {
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
});

// OnLoad Page
if (checkbox.checked) {
    box.style.display = 'block';
} else {
    box.style.display = 'none';
}
