<?php
	include_once("config/config.php");
	include_once("./sendPushNotification.php");
	
	if(isset($_POST['requestId'])){
	
		$requestId = $_POST['requestId'];
		$response = "canceled";
		
		$cancelReqQuery = "UPDATE `request` SET `status` = ? WHERE `id` = ? AND `status` != 'arrived'";
		$cancelReqResult = $conn->prepare($cancelReqQuery);
		
		if ($cancelReqResult->execute([$response, $requestId])){
			$conn->query("COMMIT");
			echo "congrats";
		}else{
			echo "sorry";
		}
		alertTheClient($conn, $requestId);
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}
	
	function alertTheClient($conn, $requestId){

		$getTokenQ = "SELECT `mechanic`.`token` FROM `mechanic` INNER JOIN `request` ON `mechanic`.`email` = `request`.`mechanic_email` AND `request`.`id` = $requestId";
		$getTokenR = $conn->query($getTokenQ);
		if ($token = $getTokenR->fetch()){
			if ($token['token'] != "null" && $token['token'] != "none"){
				sendNotification($token['token'], array("body" => "Sorry, the client cancelled the request", "title" => "Cancelation"));
			}
		}
	}
?>