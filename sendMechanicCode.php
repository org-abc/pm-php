<?php
	include_once("config/config.php");
	
	if(isset($_POST['email'])){
	
        $email = $_POST['email'];
        $code = rand(1000, 9000);
		
        $getUserQuery = "SELECT `email` FROM `mechanic` WHERE `email` = ?";
        $getUserReult = $conn->prepare($getUserQuery);
        $getUserReult->execute([$email]);
        if ($getUserReult->fetch()){
            $changeVerifCodeQuery = "UPDATE `mechanic` SET `code` = ? WHERE `email` = ?";
            $changeVerifCodeResult = $conn->prepare($changeVerifCodeQuery);
            if ($changeVerifCodeResult->execute([$code, $email])){
                sendEmail($email, "Code: ".$code, "Forgot Password");
                echo "congrats";
            }
        }
        else{
            echo "not found";
        }
	
	}else{
		echo "Ooooooooops, Something went wrong";
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