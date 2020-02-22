<?php
	include_once("config/config.php");
	
	if(isset($_POST['mechanicEmail']) && isset($_POST['lat']) && isset($_POST['lng'])){
		
		$mechanicEmail = $_POST['mechanicEmail'];
		$lat = $_POST['lat'];
		$lng = $_POST['lng'];
		
		$updateLocQuery = "UPDATE `mechanic` SET `lat` = '$lat', `lng` = '$lng' WHERE `email` = '$mechanicEmail'";
		
		$conn->query($updateLocQuery);
		$conn->query("COMMIT");

		if (/*isset($_POST['userEmail']) && */$mechanicEmail != ""){
			// $userEmail = $_POST['userEmail'];
			// $getmechanicLocQuery = "SELECT `lat`, `lng` FROM `user` WHERE `email` = '$userEmail'";
			// $getmechanicLocResult = $conn->query($getmechanicLocQuery);
			
			// $mechanicLoc = $getmechanicLocResult->fetch();
			
			// echo json_encode($mechanicLoc);
			echo "congrats";
		}
		else{
			echo "null";
		}
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}
?>