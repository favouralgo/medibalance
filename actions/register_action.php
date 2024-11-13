<?php
require_once("../controllers/user_controller.php");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $user_email = $_POST['user_email'];
    $user_facilityname = $_POST['user_facilityname'];
    $user_country = $_POST['user_country'];
    $user_city = $_POST['user_city'];
    $user_address = $_POST['user_address'];
    $user_phonenumber = $_POST['user_phonenumber'];
    $user_password = $_POST['user_password'];

    $userController = new UserController();
    $response = $userController->add_user($user_firstname, $user_lastname, $user_password, $user_phonenumber, $user_country, $user_city,$user_facilityname, $user_email, $user_address);

    if ($response) {
        echo json_encode(['success' => true, 'message' => 'Registration successful.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed.']);
    }
}
?>