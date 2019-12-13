<?php
	include_once("config/config.php");
	
	if(isset($_POST['userEmail']) && isset($_POST['mechanicEmail']) && isset($_POST['lat']) && isset($_POST['lng'])){
		
		$userEmail = $_POST['userEmail'];
		$mechanicEmail = $_POST['mechanicEmail'];
		$lat = $_POST['lat'];
		$lng = $_POST['lng'];
		
		$updateLocQuery = "UPDATE `user` SET `lat` = '$lat', `lng` = '$lng' WHERE `email` = '$userEmail'";
		
		$conn->query($updateLocQuery);
		$conn->query("COMMIT");

		if ($mechanicEmail != ""){
			$getMechanicLocQuery = "SELECT `lat`, `lng` FROM `mechanic` WHERE `email` = '$mechanicEmail'";
			$getMechanicLocResult = $conn->query($getMechanicLocQuery);
			
			$mechanicLoc = $getMechanicLocResult->fetch();
			
			echo json_encode($mechanicLoc);
		}
		else{
			echo "null";
		}
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}
?>