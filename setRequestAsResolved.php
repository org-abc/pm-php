<?php

	include_once("config/config.php");
	include_once("./sendPushNotification.php");
	
	if(isset($_POST['reqId']) && isset($_POST['status'])){
	
		$reqId = $_POST['reqId'];
		$status = $_POST['status'];
		
		$finishReqQuery = "UPDATE `request` SET `status` = ? WHERE `id` = ?";
        $finishReqResult = $conn->prepare($finishReqQuery);
		$finishReqResult->execute([$status, $reqId]);
		$conn->query("COMMIT");
		
		if ($finishReqResult){
			echo "congrats";
		}
		alertTheClient($conn, $reqId, $status);
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}
	
	function alertTheClient($conn, $requestId, $status){

		$getTokenQ = "SELECT `user`.`token` FROM `user` INNER JOIN `request` ON `user`.`email` = `request`.`user_email` AND `request`.`id` = $requestId";
		$getTokenR = $conn->query($getTokenQ);
		if ($result = $getTokenR->fetch()){
			if ($result['token'] != "null" && $result['token'] != "none"){
				$body = ($status == "Arrived" || $status == "arrived") ? "Your Mechanic has arrived at your specified destination" : "The mechanic has set the issue as resolved. Tap to rate his service";
				sendNotification($result['token'], array("body" => $body, "title" => "Arrived"));
			}
		}
	}
?>