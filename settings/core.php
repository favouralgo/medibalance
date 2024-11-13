<?php
//start session
session_start(); 

//for header redirection
ob_start();

//function to check for login
function is_logged_in(){
    if(isset($_SESSION['user_id'])){
        return true;
    }else{
        return false;
    }
}
//function to check for admin
function is_admin(){
    if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'){
        return true;
    }else{
        return false;
    }
}

//function to check for customer
function is_customer(){
    if(isset($_SESSION['role']) && $_SESSION['role'] == 'customer'){
        return true;
    }else{
        return false;
    }
}


//function to check for role (admin, customer, etc)
function get_role(){
    if(isset($_SESSION['role'])){
        return $_SESSION['role'];
    }else{
        return false;
    }
}



?>