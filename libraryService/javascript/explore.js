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

function checkoutBook(bookId) {
    // Prepare the data to be sent
    var formData = new FormData();
    formData.append('bookId', bookId);
    formData.append('checkout', true);

    // Create the AJAX request
    fetch('explore.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Book checked out successfully');
            // Update the amount on the page
            document.getElementById('amountAvailable' + bookId).textContent = data.newAmount;
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to process checkout');
    });
}

function reserveBookAsync(bookId) {
    var formData = new FormData();
    formData.append('bookId', bookId);
    formData.append('reserve', true);

    fetch('explore.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(html => {
        alert('Book reserved successfully');
        // Optionally update UI elements here
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to reserve the book');
    });

    return false; // Prevent traditional form submission
}