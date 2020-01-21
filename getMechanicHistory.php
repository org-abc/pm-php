<?php

	include_once("config/config.php");
	
	if(isset($_POST['dateCreated']) && isset($_POST['email'])){
	
		$dateCreated = $_POST['dateCreated'];
		$email = $_POST['email'];
		
		
        $getRequestQuery = "SELECT * FROM `request` WHERE `status` != 'waiting'";
        $getRequestResult = $conn->query($getRequestQuery);
        $request = $getRequestResult->fetch();
		
		while ($request){
			$requests[] = $request;
			
			$userEmail = $request['user_email'];
			$getUserDetailsQuery = "SELECT `fname`, `lname` FROM `user` WHERE `email` = ?";
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