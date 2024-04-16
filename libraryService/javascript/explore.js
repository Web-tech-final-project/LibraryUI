function updateDropdownLabel(text) {
    document.getElementById('dropdownMenuButton1').textContent = text;
}

document.addEventListener('DOMContentLoaded', function() {
    let button = document.getElementById('dropdownMenuButton1');
    let dropdownItems = document.querySelectorAll('#dropdownMenuButton1 + .dropdown-menu .dropdown-item');
    let maxWidth = 0;

    dropdownItems.forEach(item => {
        // Create a temporary button to calculate width
        let tempButton = button.cloneNode(true);
        tempButton.textContent = item.textContent;
        tempButton.style.visibility = 'hidden';
        document.body.appendChild(tempButton);

        if (tempButton.offsetWidth > maxWidth) {
            maxWidth = tempButton.offsetWidth;
        }

        document.body.removeChild(tempButton);
    });

    // Set the width of the original button
    button.style.width = `${maxWidth + 30}px`; // add some padding
});
