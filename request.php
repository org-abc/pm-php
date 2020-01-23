<?php

	include_once("config/config.php");
	include_once("distanceCalc.php");
	include_once("sendPushNotification.php");
	
	if(isset($_POST['comment']) && isset($_POST['issue']) && isset($_POST['makeAndModel']) && isset($_POST['email']) && isset($_POST['userLat']) && isset($_POST['userLng']) && isset($_POST['payment']) && isset($_POST['serviceFee'])){

		$comment = $_POST['comment'];
		$issue = $_POST['issue'];
		$email = $_POST['email'];
		$userLat = $_POST['userLat'];
		$userLng = $_POST['userLng'];
		$payment = $_POST['payment'];
		$serviceFee = $_POST['serviceFee'];
		$makeAndModel = $_POST['makeAndModel'];

		$checkforReqQ = "SELECT `id` FROM `request` WHERE `user_email` = ? AND (`status` = ? || `status` = ?)";
		$checkForReqR = $conn->prepare($checkforReqQ);
		$checkForReqR->execute([$email, 'waiting', 'accept']);
		if ($checkForReqR->fetch()){
			echo "alreadyBusy";
		}
		else{
			if (checkIfIsInArea($conn, $userLat, $userLng, $issue, $makeAndModel, $comment)){
				$insertReqQuery = "INSERT INTO `request`(`comment`, `user_email`, `status`, `user_lat`, `user_lng`, `min_service_fee`, `issue`, `make_and_model`) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
				$insertReqResult = $conn->prepare($insertReqQuery);
				$insertReqResult->execute([$comment, $email, "waiting", $userLat, $userLng, $serviceFee, $issue, $makeAndModel]);
				$conn->query("COMMIT");
				$getReqIdResult = $conn->prepare("SELECT `id` FROM `request` WHERE `min_service_fee` = ? AND `user_email` = ? AND `status`= ? ORDER BY `date_created` DESC");
				$getReqIdResult->execute([$serviceFee, $email, 'waiting']);
				$row = $getReqIdResult->fetch();
				echo "congrats:".$row['id'];
				
				if (isset($_POST['imageData']) && isset($_POST['imageName'])){

					$imageName = $_POST['imageName'];
					$imageData = $_POST['imageData'];
					$imagePath = "reqPics/$imageName.png";
					$serverUrl = $pmWebsite."/$imagePath";

					$addImageQ = "UPDATE `request` SET `img_path` = ? WHERE `id` = ?";
					$addImageR = $conn->prepare($addImageQ);
					$addImageR->execute([$serverUrl, $row['id']]);
			
					file_put_contents($imagePath, base64_decode($imageData));
				}
			}else{
				echo "outOfRange";
			}
		}
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}

	function checkIfIsInArea($conn, $lat, $lng, $issue, $car, $comment){
		
		$checkAreaQ = "SELECT `lat`, `lng`, `email`, `token` FROM `mechanic` WHERE `status` = ?";
		$checkAreaR = $conn->prepare($checkAreaQ);
		$checkAreaR->execute(['active']);
		$isIt = false;

		while ($mechanic = $checkAreaR->fetch()){
			
			$distance = distance($lat, $lng, $mechanic['lat'], $mechanic['lng'], "k");
			if ($distance <= 20){

				$checkIfAvailableQ = "SELECT `status` FROM `request` WHERE mechanic_email = ? AND `status` = ?";
				$checkIfAvailableR = $conn->prepare($checkIfAvailableQ);
				$checkIfAvailableR->execute([$mechanic['email'], "accept"]);
				if (!$checkIfAvailableR->fetch()){
					$isIt = true;
					if ($mechanic['token'] != 'null' && $mechanic['token'] != 'none'){
						$comment = ($comment != "") ? $comment : "No comment";
						$body = "There is someone in your area who need your HELP.\nIssue: $issue.\nCar: $car.\nComment: $comment";
						sendNotification($mechanic['token'], array("body" => $body, "title" => "PLEASE HELP!!!"));
					}
				}
			}
		}
		return $isIt;
	}

?>