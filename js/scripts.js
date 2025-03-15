document.addEventListener('DOMContentLoaded', () => {
    const bookingForm = document.querySelector('form[action="book.php"]');
    if (bookingForm) {
        bookingForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const formData = new FormData(bookingForm);

            fetch('book.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else if (data.success) {
                    alert(data.success);
                    window.location.href = 'profile.php';
                }
            })
            .catch(err => console.error('Error:', err));
        });
    }
});
