<?php

	include_once("config/config.php");
	include_once("./sendPushNotification.php");
	
	if(isset($_POST['requestId']) && isset($_POST['mechanicEmail'])){
	
		$requestId = $_POST['requestId'];
		$mechanicEmail = $_POST['mechanicEmail'];
		
		$acceptReqQuery = "UPDATE `request` SET `mechanic_email` = ?, `status` = ? WHERE `id` = ? AND `status` = ?";
        $acceptReqResult = $conn->prepare($acceptReqQuery);
        $acceptReqResult->execute([$mechanicEmail, "accept", $requestId, "waiting"]);
		
		if ($acceptReqResult){
			echo "congrats";
		}
		alertTheClient($conn, $requestId);
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}

	function alertTheClient($conn, $requestId){

		$getTokenQ = "SELECT `user`.`token` FROM `user` INNER JOIN `request` ON `user`.`email` = `request`.`user_email` AND `request`.`id` = $requestId";
		$getTokenR = $conn->query($getTokenQ);
		if ($token = $getTokenR->fetch()){
			if ($token['token'] != "null" && $token['token'] != "none"){
				sendNotification($token['token'], array("body" => "A Mechanic is comming to the rescure", "title" => "Hoory!"));
			}
		}
	}
?>