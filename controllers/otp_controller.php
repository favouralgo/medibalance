<?php
require_once("../classes/otp_class.php");

/**
 * Generates a One-Time Password (OTP) for the given email.
 *
 * This function creates an instance of the OTP class and calls its generate_otp method
 * to generate a one-time password for the specified email address.
 *
 * @param string $email The email address for which the OTP is to be generated.
 * @return string The generated OTP.
 */
function generate_otp_ctr($email) {
    $otp = new OTP();
    return $otp->generate_otp($email);
}

/**
 * Validate OTP Controller
 *
 * This function validates the OTP (One-Time Password) for a given email.
 *
 * @param string $email The email address to validate the OTP for.
 * @param string $otp The OTP to be validated.
 * @return bool Returns true if the OTP is valid, false otherwise.
 */
function validate_otp_ctr($email, $otp) {
    $otp_instance = new OTP();
    return $otp_instance->validate_otp($email, $otp);
}
?>