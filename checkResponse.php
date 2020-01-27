<?php
	include_once("config/config.php");
	
	if(isset($_POST['requestId'])){
		
		$requestId = $_POST['requestId'];
		
        $checkQuery = "SELECT `status`, `mechanic_email` FROM `request` WHERE  `id` = ?";
        $checkResult = $conn->prepare($checkQuery);
        $checkResult->execute([$requestId]);
		
		$row = $checkResult->fetch();
		$response = $row['status'];
		$mechanicEmail = $row['mechanic_email'];
		
		if ($mechanicEmail == ""){
			echo $response.":";
		}
		else{
			$getMechanicDetailsQuery = "SELECT `lat`, `lng`, `phone`, `image_path`, `email`, `fname`, `lname`, `rating` FROM `mechanic` WHERE `email` = ?";
			$getMechanicDetailsResult = $conn->prepare($getMechanicDetailsQuery);
			$getMechanicDetailsResult->execute([$mechanicEmail]);
			
			while ($mechanic = $getMechanicDetailsResult->fetch()){
				$mechanics[] = $mechanic;
			}
			echo $response.":".json_encode($mechanics);
		}
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}
?>