import './bootstrap';
import Swal from 'sweetalert2';

// Check if there's a success message in the session and show SweetAlert
document.addEventListener('DOMContentLoaded', function() {
    // Check if success message exists in meta tag
    const successMessage = document.querySelector('meta[name="success-message"]');
    if (successMessage) {
        Swal.fire({
            title: 'Success!',
            text: successMessage.getAttribute('content'),
            icon: 'success',
            confirmButtonText: 'OK',
            timer: 3000,
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    }
    
    // Add event listener for logout button to show confirmation
    const logoutButtons = document.querySelectorAll('form[method="POST"][action$="/logout"] button[type="submit"]');
    logoutButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to logout?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form if confirmed
                    button.closest('form').submit();
                }
            });
        });
    });
});
