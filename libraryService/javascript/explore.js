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

function confirmCheckout(bookId, bookTitle) {
    // Send an AJAX request to check if the book is already checked out
    fetch('checkCheckoutStatus.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'bookId=' + bookId
    })
    .then(response => response.json())
    .then(data => {
        if (data.hasCheckedOut) {
            alert("You have already checked out this book.");
        } else {
            if (confirm("Please confirm your checkout of " + bookTitle)) {
                // Submit the form if confirmed
                document.getElementById('checkoutForm' + bookId).submit();
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

