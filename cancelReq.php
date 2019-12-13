<?php
	include_once("config/config.php");
	
	if(isset($_POST['requestId'])){
	
		$requestId = $_POST['requestId'];
		$response = "canceled";
		
		$checkIfAcceptedQ = "SELECT * FROM `request` WHERE `id` = ? AND `status` = 'waiting'";
		$checkIfAcceptedR = $conn->prepare($checkIfAcceptedQ);
		$checkIfAcceptedR->execute([$requestId]);

		if ($checkIfAcceptedR->fetch()){
			$cancelReqQuery = "UPDATE `request` SET `status` = ? WHERE `id` = ? AND `status` = ?";
			$cancelReqResult = $conn->prepare($cancelReqQuery);
			$cancelReqResult->execute([$response, $requestId, "waiting"]);
			$conn->query("COMMIT");
			
			if ($cancelReqResult){
				echo "congrats";
			}else{
				echo "sorry";
			}
		}
		else{
			echo "sorry";
		}
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}
?>