<?php

	include_once("config/config.php");
	
	if(isset($_POST['activity']) && isset($_POST['email'])){
		
		$act = $_POST['activity'];
		$email = $_POST['email'];
		$status = ($act == "activate") ? "active" : "inactive";
		
		$updateMechanicStatusQ = "UPDATE `mechanic` SET `status` = ? WHERE `email` = ?";
		$updateMechanicStatusR = $conn->prepare($updateMechanicStatusQ);
		
		if ($updateMechanicStatusR->execute([$status, $email])){
			$conn->query("COMMIT");
			echo "congrats";
		}
		else{
			echo "sorry";
		}
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}

?>