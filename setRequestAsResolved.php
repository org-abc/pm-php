<?php

	include_once("config/config.php");
	include_once("./sendPushNotification.php");
	
	if(isset($_POST['reqId'])){
	
		$reqId = $_POST['reqId'];
		
		$finishReqQuery = "UPDATE `request` SET `status` = ? WHERE `id` = ?";
        $finishReqResult = $conn->prepare($finishReqQuery);
        $finishReqResult->execute(["arrived", $reqId]);
		
		if ($finishReqResult){
			echo "congrats";
		}
		alertTheClient($conn, $reqId);
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}
	
	function alertTheClient($conn, $requestId){

		$getTokenQ = "SELECT `user`.`token` FROM `user` INNER JOIN `request` ON `user`.`email` = `request`.`user_email` AND `request`.`id` = $requestId";
		$getTokenR = $conn->query($getTokenQ);
		if ($token = $getTokenR->fetch()){
			if ($token['token'] != "null" && $token != "none"){
				sendNotification($token['token'], array("body" => "Your Mechanic has arrived at your specified destination", "title" => "Arrived"));
			}
		}
	}
?>