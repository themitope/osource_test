<?php
require_once('db_connect.php');
require_once('constants.php');

class DbQueries{
  public function __construct(){
       //$this->connection = mysqli_connect('localhost', 'f42v5vy0h3bw_app2_farmkonnect', 'f42v5vy0h3bw_app2_farmkonnect', 'f42v5vy0h3bw_app2_farmkonnect');
  $this->connection = mysqli_connect('localhost', 'root', '', 'osource_test');
    if(mysqli_connect_error()){
      die("Database Connection Failed" . mysqli_connect_error() . mysqli_connect_errno());
    }
  }
  public function returnResponse($code, $data, $response=null){
  //header("content-type: application/json");
  $response = json_encode(['response'=>["status"=>$code, "message"=>$data, "data"=>$response]]);
  echo $response;
  }

  function secure_database($value){
    $value = mysqli_real_escape_string($this->connection, $value);
    return $value;
  }

  function unique_id_generator($data){
     $data = $this->secure_database($data);
     $newid = md5(uniqid().time().rand(11111, 99999).rand(11111,99999).$data);
     return $newid;
  }
  function get_rows_from_one_table_by_id($table,$theid,$idvalue){
  $table = $this->secure_database($table);
  $theid = $this->secure_database($theid);
  $idvalue = $this->secure_database($idvalue);
  $sql = "SELECT * FROM `$table` WHERE `$theid`='$idvalue'";
  $query = mysqli_query($this->connection, $sql);
  $num = mysqli_num_rows($query);
  if($num > 0){
      while($row = mysqli_fetch_array($query)){
          $row_display[] = $row;
          }
      return $row_display;
    }
    else{
      return null;
    }
  }

  function get_one_row_from_one_table($table,$param,$value){
    $table = $this->secure_database($table);
    $param = $this->secure_database($param);
    $value = $this->secure_database($value);
    $sql = "SELECT * FROM `$table` WHERE `$param` = '$value'";
    $query = mysqli_query($this->connection, $sql);
    $num = mysqli_num_rows($query);
   if($num > 0){
      $row = mysqli_fetch_assoc($query);
      return $row;
    }
    else{
       return null;
    }
  }

  function image_upload($filename, $size, $tmpName, $type){


  //$currentDir = getcwd();
  //$dir =  dirname(__DIR__);

  //$uploadPath = $dir.'/uploads';
  //$uploadPath = "https://".$_SERVER['HTTP_HOST']."/uploads";
  $uploadPath= "uploads/".$filename;
  //$uploadPath = "https://$_SERVER[HTTP_HOST]"."/uploads/".$filename;
  $fileExtensions = ['jpeg','jpg','png', 'pdf','xlsx','csv','docx','doc'];
  $fileExtension = substr($filename, strpos($filename, '.') + 1);

  @$fileExtension = strtolower(end(explode('.',$filename)));
  // $uploadPath = $currentDir . $file_path . basename($filename);
  if (!in_array($fileExtension,$fileExtensions)) {
   return json_encode(["status"=>"0", "msg"=>"This file extension is not allowed. Please upload a JPEG or PNG file"]);
  }
  else{
     if ($size > 2000000) {
      return json_encode(["status"=>"0", "msg"=>"File size is more than 2MB"]);
    }
    else{
      $didUpload = move_uploaded_file($tmpName, $uploadPath);
      if ($didUpload) {
        return json_encode(["status"=>"1", "msg"=>$uploadPath]);
      }
      else{
        return json_encode(["status"=>"0", "msg"=>"Server Error"]);
      }
    }
  }
  }


  function update_with_one_param($table,$param,$value,$new_value_param,$new_value){
    $table = $this->secure_database($table);
    $value = $this->secure_database($value);
    $param = $this->secure_database($param);
    $new_value_param = $this->secure_database($new_value_param);
    $new_value = $this->secure_database($new_value);
    $sql = "UPDATE `$table` SET `$new_value_param`='$new_value' WHERE `$param` = '$value'";
    $query = mysqli_query($this->connection, $sql)or die(mysqli_error($this->connection));
    
    if($query){
        return json_encode(["status"=>"1", "msg"=>"success"]);
        
    }
    else{
      return json_encode(["status"=>"0", "msg"=>"db_error"]);
    }
  }

  function check_row_exists_by_one_param($table,$param,$value){
    $table = $this->secure_database($table);
    $param = $this->secure_database($param);
    $value = $this->secure_database($value);
    $sql = "SELECT * FROM `$table` WHERE `$param` = '$value'";
    $query = mysqli_query($this->connection, $sql);
    $num = mysqli_num_rows($query);
    if($num > 0 ){
      return true;
    }else{
      return false;
    }
  }

  function post_user_data($first_name, $last_name, $email, $phone, $gender, $password, $confirm_password){
    $unique_id = $this->unique_id_generator($email);
    $first_name = $this->secure_database($first_name);
    $last_name = $this->secure_database($last_name);
    $email = $this->secure_database($email);
    $phone = $this->secure_database($phone);
    $gender = $this->secure_database($gender);
    $check_user_exist = $this->check_row_exists_by_one_param('users', 'email' ,$email);
    if($first_name == "" || $last_name == '' || $password == "" || $confirm_password == "" || $phone == "" || $email == "" || $gender == ''){
      http_response_code(400);
      return $this->returnResponse(VALIDATE_PARAMETER_REQUIRED, "Empty field(s) found", []);
    }
    else if (ctype_alpha(str_replace(' ', '', $first_name)) === false || ctype_alpha(str_replace(' ', '', $last_name)) === false) {
      http_response_code(400);
      return $this->returnResponse(VALIDATE_PARAMETER_REQUIRED, "Invalid Name format");
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      http_response_code(400);
      return $this->returnResponse(VALIDATE_PARAMETER_REQUIRED, "Invalid Email format");
    }
    else if (strlen($password) < 8) {
      http_response_code(400);
      return $this->returnResponse(VALIDATE_PARAMETER_REQUIRED, "Your Password Must Contain At Least 8 Characters!");
    }
    else if($check_user_exist){
      http_response_code(400);
      return $this->returnResponse(USER_EXISTS, "User already exists", []);
    }
    else if ($password != $confirm_password){
      http_response_code(400);
      return $this->returnResponse(PASSWORD_MISMATCH, "Passwords do not match");
    }
    else{
      $enc_password = md5($password);
      $sql = "INSERT INTO `users` SET `unique_id` = '$unique_id',`first_name` = '$first_name', `last_name` = '$last_name',`email` = '$email',  `phone` = '$phone', `gender` = '$gender', `password` = '$enc_password', `date_created` = now()";
      $query = mysqli_query($this->connection, $sql) or die(mysqli_error($this->connection));
      if($query){
        $user_data = $this->get_one_row_from_one_table('users','email',$email);
        return $this->returnResponse(SUCCESS_RESPONSE, "User details saved successfully", $user_data);
      }
    }
  }

  function update_user_data($first_name, $last_name, $email, $phone, $gender, $password, $confirm_password){
    $first_name = $this->secure_database($first_name);
    $last_name = $this->secure_database($last_name);
    $email = $this->secure_database($email);
    $phone = $this->secure_database($phone);
    $gender = $this->secure_database($gender);
    if($first_name == "" || $last_name == '' || $password == "" || $confirm_password == "" || $phone == "" || $email == "" || $gender == ''){
      http_response_code(400);
      return $this->returnResponse(VALIDATE_PARAMETER_REQUIRED, "Empty field(s) found", []);
    }
    else if (ctype_alpha(str_replace(' ', '', $first_name)) === false || ctype_alpha(str_replace(' ', '', $last_name)) === false) {
      http_response_code(400);
      return $this->returnResponse(VALIDATE_PARAMETER_REQUIRED, "Invalid Name format");
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      http_response_code(400);
      return $this->returnResponse(VALIDATE_PARAMETER_REQUIRED, "Invalid Email format");
    }
    else if (strlen($password) < 8) {
      http_response_code(400);
      return $this->returnResponse(VALIDATE_PARAMETER_REQUIRED, "Your Password Must Contain At Least 8 Characters!");
    }
    else if ($password != $confirm_password){
      http_response_code(400);
      return $this->returnResponse(PASSWORD_MISMATCH, "Passwords do not match");
    }
    else{
      $enc_password = md5($password);
      $sql = "UPDATE `users` SET `first_name` = '$first_name', `last_name` = '$last_name',`email` = '$email',  `phone` = '$phone', `gender` = '$gender', `password` = '$enc_password' WHERE `email` = '$email'";
      $query = mysqli_query($this->connection, $sql) or die(mysqli_error($this->connection));
      if($query){
        $user_data = $this->get_one_row_from_one_table('users','email',$email);
        return $this->returnResponse(SUCCESS_RESPONSE, "User details updated successfully", $user_data);
      }
    }
  }

}
?>