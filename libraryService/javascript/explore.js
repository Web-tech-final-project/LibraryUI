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


document.addEventListener('DOMContentLoaded', function() {
    const createReviewButtons = document.querySelectorAll('.create-review-btn');
    createReviewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const bookId = this.getAttribute('data-bookid');
            document.getElementById('bookIdInput').value = bookId;
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating');

    // Handle hover event
    stars.forEach(star => {
        star.addEventListener('mouseenter', function() {
            const rating = this.getAttribute('data-rating');
            highlightStars(rating);
        });

        star.addEventListener('mouseleave', function() {
            const currentRating = ratingInput.value;
            highlightStars(currentRating);
        });

        // Handle click event
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            ratingInput.value = rating;
            highlightStars(rating);
            fillStars(rating);
        });
    });

    // Function to highlight stars up to the specified rating
    function highlightStars(rating) {
        stars.forEach(star => {
            const starRating = star.getAttribute('data-rating');
            if (starRating <= rating) {
                star.classList.add('text-warning');
            } else {
                star.classList.remove('text-warning');
            }
        });
    }

    // Function to fill stars up to the specified rating
    function fillStars(rating) {
        stars.forEach(star => {
            const starRating = star.getAttribute('data-rating');
            if (starRating <= rating) {
                star.classList.remove('bi-star');
                star.classList.add('bi-star-fill');
            } else {
                star.classList.remove('bi-star-fill');
                star.classList.add('bi-star');
            }
        });
    }
});