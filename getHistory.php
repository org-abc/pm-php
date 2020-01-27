<?php

	include_once("config/config.php");
	
	if(isset($_POST['dateCreated']) && isset($_POST['email'])){
	
		$dateCreated = $_POST['dateCreated'];
		$email = $_POST['email'];
		
		
        $getRequestQuery = "SELECT * FROM `request` WHERE `user_email` = ? AND `date_created` < ? ORDER BY `date_created` DESC LIMIT 10";
		$getRequestResult = $conn->prepare($getRequestQuery);
		$getRequestResult->execute([$email, $dateCreated]);
        $request = $getRequestResult->fetch();
		
		if (!$request){
			exit("empty");
		}
		while ($request){
			$requests[] = $request;
			
			$mechanicEmail = $request['mechanic_email'];
			$getMechanicDetailsQuery = "SELECT `fname`, `lname` FROM `mechanic` WHERE `email` = ?";
			$getMechanicDetailsResult = $conn->prepare($getMechanicDetailsQuery);
			$getMechanicDetailsResult->execute([$mechanicEmail]);

			$mechanics[] = $getMechanicDetailsResult->fetch();
			$request = $getRequestResult->fetch();
		}
		echo Json_encode(array('mechanics'=>$mechanics, 'requests'=>$requests));
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}
?>