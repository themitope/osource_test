<?php
 
class Database{
	
	public $connection;
    //public $localhost = 'localhost';
 
	public function toconnect(){

		$this->connection = mysqli_connect('localhost', 'root', '', 'osource_test');
		 //$this->connection = mysqli_connect('localhost', 'root', 'rnTDcDDuEEbqkj64', 'fk_may_2020');
		// $this->connection = mysqli_connect('localhost', 'f42v5vy0h3bw_app2_farmkonnect', 'f42v5vy0h3bw_app2_farmkonnect', 'f42v5vy0h3bw_app2_farmkonnect');

		if(mysqli_connect_error()){
			die("Database Connection Failed" . mysqli_connect_error() . mysqli_connect_errno());
		}
	}


}
 
?>