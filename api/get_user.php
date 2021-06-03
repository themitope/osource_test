<?php 
header('Content-Type: application/json');
    require_once('../classes/db_class.php');
    require_once('../classes/constants.php');
    $object = new DbQueries();
    $email = $_GET['email'];
	$get_user =   $object->get_one_row_from_one_table('users','email', $email);;
    echo $object->returnResponse(SUCCESS_RESPONSE, "User details retrieved successfully", $get_user);
   
?>