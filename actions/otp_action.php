<?php
session_start();
require_once("../controllers/otp_controller.php");
require_once("../PHPMailer/PHPMailer.php");
require_once("../PHPMailer/Exception.php");
require_once("../PHPMailer/SMTP.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Handles OTP generation and validation based on the POST request.
 * 
 * This script processes two types of actions:
 * 1. 'generate' - Generates an OTP for the provided email and sends it to the user's email.
 * 2. 'validate' - Validates the provided OTP against the stored OTP for the provided email.
 * 
 * POST Parameters:
 * - action: The action to perform ('generate' or 'validate').
 * - email: The email address for which the OTP is generated or validated.
 * - otp: The OTP to validate (required only for 'validate' action).
 * 
 * Responses:
 * - On 'generate' action success: {"success": true, "message": "OTP sent to your email"}
 * - On 'validate' action success: {"success": true, "message": "OTP validated successfully"}
 * - On 'validate' action failure: {"success": false, "message": "Invalid or expired OTP"}
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $email = $_POST['email'];

    if ($action == 'generate') {
        $otp = generate_otp_ctr($email);
        if (send_otp_email($email, $otp)) {
            echo json_encode(["success" => true, "message" => "OTP sent to your email"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to send OTP email"]);
        }
    } elseif ($action == 'validate') {
        $otp = $_POST['otp'];
        if (validate_otp_ctr($email, $otp)) {
            echo json_encode(["success" => true, "message" => "OTP validated successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid or expired OTP"]);
        }
    }
}

/**
 * Sends an OTP email to the specified email address.
 *
 * @param string $email The recipient's email address.
 * @param string $otp The OTP to send.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function send_otp_email($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'favourmdev@gmail.com'; // SMTP username
        $mail->Password = '../password.txt'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('your_email@gmail.com', 'Your Name');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Hello, your OTP code is <b>$otp</b>. Kindly use this code immediately, as it will expire in 3 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>