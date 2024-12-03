$(document).ready(function() {
    // Toggle password visibility
    $('.toggle-password').click(function() {
        const input = $($(this).attr('toggle'));
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Form submission
    $('#registrationForm').on('submit', function(e) {
        e.preventDefault();

        // Validate form
        if (!validateForm()) {
            return;
        }

        // Collect form data
        const formData = {
            admin_firstname: $('#admin_firstname').val(),
            admin_lastname: $('#admin_lastname').val(),
            admin_email: $('#admin_email').val(),
            admin_password: $('#admin_password').val(),
            admin_country: $('#admin_country').val(),
            admin_city: $('#admin_city').val(),
            admin_phonenumber: $('#admin_phonenumber').val(),
            admin_address: $('#admin_address').val()
        };

        // Submit form
        $.ajax({
            url: '../actions/admin_register_action.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = response.redirect;
                        }
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'An error occurred. Please try again.', 'error');
            }
        });
    });

    // Form validation
    function validateForm() {
        const password = $('#admin_password').val();
        const confirmPassword = $('#confirm_password').val();
        const email = $('#admin_email').val();
        const phone = $('#admin_phonenumber').val();

        // Check password match
        if (password !== confirmPassword) {
            Swal.fire('Error', 'Passwords do not match', 'error');
            return false;
        }

        // Check password length
        if (password.length < 8) {
            Swal.fire('Error', 'Password must be at least 8 characters long', 'error');
            return false;
        }

        // Validate email format
        if (!validateEmail(email)) {
            Swal.fire('Error', 'Please enter a valid email address', 'error');
            return false;
        }

        // Validate phone number
        if (!validatePhone(phone)) {
            Swal.fire('Error', 'Please enter a valid phone number', 'error');
            return false;
        }

        return true;
    }

    // Email validation
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Phone validation
    function validatePhone(phone) {
        const re = /^\+?[1-9]\d{1,14}$/;
        return re.test(phone);
    }
});