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

    $('#loginForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Validate form
        if (!validateForm()) {
            return;
        }

        // Perform the AJAX request
        $.ajax({
            url: '../actions/login_action.php',
            type: 'POST',
            data: {
                user_email: $('#user_email').val(),
                user_password: $('#user_password').val()
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire("Success", response.message, "success").then(() => {
                        window.location.href = response.redirect;
                    });
                } else {
                    Swal.fire("Error", response.message, "error");
                }
            },
            error: function() {
                console.log('Server response:', xhr.responseText);
                Swal.fire("Error", "An error occurred. Please try again.", "error");
            }
        });
    });
});

function validateForm() {
    var email = $('#user_email').val().trim();
    var password = $('#user_password').val().trim();
    
    // Validate email
    if (!validateEmail(email)) {
        Swal.fire('Error', 'Please enter a valid email address', 'error');
        return false;
    }
    
    // Validate password
    if (password.length < 8) {
        Swal.fire('Error', 'Password must be at least 8 characters long.', 'error');
        return false;
    }
    
    return true;
}

function validateEmail(email) {
    var re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return re.test(email);
}