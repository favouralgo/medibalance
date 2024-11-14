// setpassword.js
$(document).ready(function() {
    // Password visibility toggle
    $('.set-password-toggle').click(function() {
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
    $('#setPasswordForm').on('submit', function(e) {
        e.preventDefault();

        if (!validateSetPasswordForm()) {
            return;
        }

        $.ajax({
            url: '../actions/set_password_action.php',
            type: 'POST',
            data: {
                customer_email: $('#customer_email').val(),
                new_password: $('#new_password').val(),
                confirm_password: $('#confirm_password').val()
            },
            dataType: 'json',
            success: handleSetPasswordResponse,
            error: handleSetPasswordError
        });
    });
});

function validateSetPasswordForm() {
    const newPassword = $('#new_password').val().trim();
    const confirmPassword = $('#confirm_password').val().trim();
    
    if (!validatePasswordLength(newPassword)) {
        Swal.fire('Error', 'Password must be at least 8 characters long.', 'error');
        return false;
    }
    
    if (!validatePasswordMatch(newPassword, confirmPassword)) {
        Swal.fire('Error', 'Passwords do not match.', 'error');
        return false;
    }

    if (!validatePasswordStrength(newPassword)) {
        Swal.fire('Error', 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.', 'error');
        return false;
    }
    
    return true;
}

function validatePasswordLength(password) {
    return password.length >= 8;
}

function validatePasswordMatch(password1, password2) {
    return password1 === password2;
}

function validatePasswordStrength(password) {
    const strongPassword = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])");
    return strongPassword.test(password);
}

function handleSetPasswordResponse(response) {
    if (response.success) {
        Swal.fire({
            title: "Success",
            text: response.message,
            icon: "success",
            showConfirmButton: true
        }).then(() => {
            window.location.href = '../login/login.php';
        });
    } else {
        Swal.fire({
            title: "Error",
            text: response.message,
            icon: "error"
        });
    }
}

function handleSetPasswordError() {
    Swal.fire({
        title: "Error",
        text: "An error occurred while setting the password. Please try again.",
        icon: "error"
    });
}