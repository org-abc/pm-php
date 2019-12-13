<?php

	include_once("config/config.php");
	
	if(isset($_POST['requestId']) && isset($_POST['mechanicEmail'])){
	
		$requestId = $_POST['requestId'];
		$mechanicEmail = $_POST['mechanicEmail'];
		
		$acceptReqQuery = "UPDATE `request` SET `mechanic_email` = ?, `status` = ? WHERE `id` = ? AND `status` = ?";
        $acceptReqResult = $conn->prepare($acceptReqQuery);
        $acceptReqResult->execute([$mechanicEmail, "accept", $requestId, "waiting"]);
		
		if ($acceptReqResult){
			echo "congrats";
		}
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}
?>