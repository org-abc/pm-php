<?php

	include_once("config/config.php");
	
	if(isset($_POST['reqId'])){
	
		$reqId = $_POST['reqId'];
		
		$finishReqQuery = "UPDATE `request` SET `status` = ? WHERE `id` = ?";
        $finishReqResult = $conn->prepare($finishReqQuery);
        $finishReqResult->execute(["arrived", $reqId]);
		
		if ($finishReqResult){
			echo "congrats";
		}
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}
?>