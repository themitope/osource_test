<?php 
header('Content-Type: application/json');
    require_once('../classes/db_class.php');
    require_once('../classes/constants.php');
    $object = new DbQueries();
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
	$update_user =  $object->update_user_data($first_name, $last_name, $email, $phone, $gender, $password, $confirm_password);
    echo $update_user;
   
?>