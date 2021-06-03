<?php

class Helper{

    private $error_array = array();

    public function proper_redirect($to = null){

    //   echo "<script>console.log(".$to.");</script>";

        if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {

            $protocol = "https://";
        
        }else {
        
            $protocol = "http://";
        
        }

        if($_SERVER['HTTP_HOST'] == "localhost"){

          if($to === 'login'){

            header("location:".$protocol.$_SERVER['HTTP_HOST']."/peer-to-peer/admin/login.php");

          }else{
    
            header("location:".$protocol.$_SERVER['HTTP_HOST']."/peer-to-peer");
          }
    
        }else {

          if($to === 'login'){

            header("location:".$protocol.$_SERVER['HTTP_HOST']."/admin/login.php");

          }else{
    
            header("location:".$protocol.$_SERVER['HTTP_HOST']);

          }

        }
        // return $url;
    }

    public function error_message($message){

      array_push($this->error_array, $message);

      $_SESSION["error"] = $this->error_array;


    }

    public function display_error_message(){

      if (isset($_SESSION["error"])){ 

        foreach($_SESSION["error"] as $error){

          echo "<div class='alert alert-warning' role='alert'>";
          echo $error . "<br/>";
          echo "</div>";
          

        }

        unset($_SESSION["error"]);

      }

    }

}
?>