function updateDropdownLabel(text, type) {
    document.getElementById('dropdownMenuButton1').textContent = text;
    document.getElementById('searchType').value = type;
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
    button.style.width = `${maxWidth + 30}px`;
});

function confirmCheckout(form, bookTitle) {
    const message = "Please confirm your checkout of '" + bookTitle + ".'";
    if (confirm(message)) {
        // If the user confirms, proceed with the form submission
        form.submit();
    } else {
        // If the user does not confirm, prevent form submission
        return false;
    }
}
