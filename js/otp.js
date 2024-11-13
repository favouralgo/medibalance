$(document).ready(function() {
    $('#validateOtpForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Collect OTP from input boxes
        var otp = $('#otp1').val() + $('#otp2').val() + $('#otp3').val() + $('#otp4').val() + $('#otp5').val() + $('#otp6').val();

        // Perform the AJAX request to validate OTP
        $.ajax({
            url: '../actions/otpprocess.php',
            type: 'POST',
            data: {
                action: 'validate',
                email: $('#email').val(),
                otp: otp
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire("Success", response.message, "success").then(() => {
                        window.location.href = '../view/login.php';
                    });
                } else {
                    Swal.fire("Error", response.message, "error");
                }
            },
            error: function() {
                Swal.fire("Error", "An error occurred. Please try again.", "error");
            }
        });
    });

    // Move to next input box on input
    $('.otp-inputs input').on('input', function() {
        if (this.value.length === this.maxLength) {
            $(this).next('input').focus();
        }
    });

    // Add fade-in effect on page load
    document.addEventListener("DOMContentLoaded", function() {
        document.body.classList.add('fade-in');
    });

    // Add fade-out effect on link click
    document.querySelectorAll('a').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            document.body.classList.add('fade-out');
            setTimeout(function() {
                window.location.href = href;
            }, 500);
        });
    });
});