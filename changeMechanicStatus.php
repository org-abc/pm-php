<?php

	include_once("config/config.php");
	include_once("./sendPushNotification.php");
	
	if(isset($_POST['activity']) && isset($_POST['email'])){
		
		$act = $_POST['activity'];
		$email = $_POST['email'];
		$status = ($act == "activate") ? "active" : "inactive";
		
		$updateMechanicStatusQ = "UPDATE `mechanic` SET `status` = ? WHERE `email` = ?";
		$updateMechanicStatusR = $conn->prepare($updateMechanicStatusQ);
		
		if ($updateMechanicStatusR->execute([$status, $email])){
			$conn->query("COMMIT");
			$msg = ($status == "active") ? "Hooray, your account have been activated. You can now get to work" : "Sorry, your account have been deactivated. Contact customer care at $companyPhone or $companyEmail for more information";
			alertTheClient($conn, $email, $msg);
			sendEmail($email, $msg, "Account status");
			echo "congrats";
		}
		else{
			echo "sorry";
		}
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}
	
	function alertTheClient($conn, $email, $msg){

		$getTokenQ = "SELECT `token` FROM `mechanic` WHERE `email` = ?";
		$getTokenR = $conn->prepare($getTokenQ);
		$getTokenR->execute([$email]);
		if ($token = $getTokenR->fetch()){
			if ($token['token'] != "null" && $token['token'] != "none"){
				sendNotification($token['token'], array("body" => $msg, "title" => "Account status"));
			}
		}
	}
    
    function sendEmail($to, $msg, $sbj)
    {
        ini_set( 'display_errors', 1 );
        error_reporting( E_ALL );
        $from = "www.kondie@live.com";
        $header = "From:" . $from;

        mail($to, $sbj, $msg, $header);
    }

?>