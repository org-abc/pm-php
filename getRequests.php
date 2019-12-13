<?php

	include_once("config/config.php");
	
	if(isset($_POST['dateCreated']) && isset($_POST['mechanicEmail'])){
	
		$dateCreated = $_POST['dateCreated'];
		$mechanicEmail = $_POST['mechanicEmail'];
		
		$getRequestQuery = "SELECT * FROM `request` WHERE (`mechanic_email` = ? AND `status` = 'accept')";
		$getRequestResult = $conn->prepare($getRequestQuery);
		$getRequestResult->execute([$mechanicEmail]);
		
		if (!($request = $getRequestResult->fetch())){
			$getRequestQuery = "SELECT * FROM `request` WHERE `status` = 'waiting'";
			$getRequestResult = $conn->prepare($getRequestQuery);
			$getRequestResult->execute([]);
			$request = $getRequestResult->fetch();
		}
		
		while ($request){
			$requests[] = $request;
			
			$userEmail = $request['user_email'];
			$getUserDetailsQuery = "SELECT `fname`, `lname`, `phone`, `image_path` FROM `user` WHERE `email` = ?";
			$getUserDetailsResult = $conn->prepare($getUserDetailsQuery);
			$getUserDetailsResult->execute([$userEmail]);

			$users[] = $getUserDetailsResult->fetch();
			$request = $getRequestResult->fetch();
		}
		echo Json_encode(array('users'=>$users, 'requests'=>$requests));
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}
?>