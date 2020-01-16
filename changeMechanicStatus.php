<?php

	include_once("config/config.php");
	
	if(isset($_POST['activity']) && isset($_POST['email'])){
		
		$act = $_POST['activity'];
		$email = $_POST['email'];
		$status = ($act == "activate") ? "active" : "inactive";
		
		$updateMechanicStatusQuery = "UPDATE `mechanic` SET `status` = '$status' WHERE `email` = '$email'";
		
		$conn->query($updateMechanicStatusQuery);
		
		echo "congrats";
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}

?>