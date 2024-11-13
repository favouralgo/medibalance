$(document).ready(function() {
    $('.toggle-password').click(function() {
        var input = $($(this).attr('toggle'));
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    $('#registrationForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Validate form
        if (!validateForm()) {
            return;
        }

        // Perform the AJAX request to register the user
        $.ajax({
            url: '../actions/register_action.php',
            type: 'POST',
            data: {
                user_firstname: $('#user_firstname').val(),
                user_lastname: $('#user_lastname').val(),
                user_email: $('#user_email').val(),
                user_facilityname: $('#user_facilityname').val(),
                user_country: $('#user_country').val(),
                user_city: $('#user_city').val(),
                user_address: $('#user_address').val(),
                user_phonenumber: $('#user_phonenumber').val(),
                user_password: $('#user_password').val(),
                confirm_password: $('#confirm_password').val()
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful',
                        text: response.message
                    }).then(function() {
                        window.location.href = 'login.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Failed',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing your request.'
                });
            }
        });
    });

    function validateForm() {
        var firstName = $('#user_firstname').val();
        var lastName = $('#user_lastname').val();
        var email = $('#user_email').val();
        var facilityName = $('#user_facilityname').val();
        var country = $('#user_country').val();
        var city = $('#user_city').val();
        var address = $('#user_address').val();
        var phoneNumber = $('#user_phonenumber').val();
        var password = $('#user_password').val();
        var confirmPassword = $('#confirm_password').val();

        if (!firstName || !lastName || !email || !facilityName || !country || !city || !address || !phoneNumber || !password || !confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'All fields are required.'
            });
            return false;
        }

        if (!validateEmail(email)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Invalid email format. Email must end with @gmail.com.'
            });
            return false;
        }

        if (!validateText(firstName)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'First name cannot contain only numbers.'
            });
            return false;
        }

        if (!validateText(lastName)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Last name cannot contain only numbers.'
            });
            return false;
        }

        if (!validateText(facilityName)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Facility name cannot contain only numbers.'
            });
            return false;
        }

        if (!validateText(country)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Country cannot contain only numbers.'
            });
            return false;
        }

        if (!validateText(city)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'City cannot contain only numbers.'
            });
            return false;
        }

        if (!validateContact(phoneNumber)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Invalid contact number format. It should start with a country code (e.g., +233) followed by 9 digits.'
            });
            return false;
        }

        if (password !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Passwords do not match.'
            });
            return false;
        }

        return true;
    }

    function validateEmail(email) {
        var re = /^[^\s@]+@(gmail\.com)$/;
        return re.test(email);
    }

    function validateText(text) {
        var re = /^(?!\d+$)[a-zA-Z\d]+$/;
        return re.test(text);
    }

    function validateContact(contact) {
        var re = /^\+233\d{9}$/;
        return re.test(contact);
    }
});