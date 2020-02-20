<?php
	include_once("config/config.php");
    include_once("methods.php");
	
	if(isset($_POST['email'])){
	
        $email = $_POST['email'];
        $code = rand(1000, 9000);
		
        $getUserQuery = "SELECT `email` FROM `user` WHERE `email` = ?";
        $getUserReult = $conn->prepare($getUserQuery);
        $getUserReult->execute([$email]);
        if ($getUserReult->fetch()){
            $changeVerifCodeQuery = "UPDATE `user` SET `code` = ? WHERE `email` = ?";
            $changeVerifCodeResult = $conn->prepare($changeVerifCodeQuery);
            if ($changeVerifCodeResult->execute([$code, $email])){
                sendEmail($email, "Code: ".$code, "Forgot Password", $companyEmail);
                echo "congrats";
            }
        }
        else{
            echo "not found";
        }
	
	}else{
		echo "Ooooooooops, Something went wrong";
    }
?>