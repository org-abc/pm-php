<?php
	include_once("config/config.php");
	
	if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['code'])){
	
        $email = $_POST['email'];
        $code = $_POST['code'];
        $password = hash('whirlpool', $_POST['password']);
		
        $changePasswordQuery = "UPDATE `mechanic` SET `password` = ? WHERE `email` = ? AND `code` = ?";
        $changePasswordResult = $conn->prepare($changePasswordQuery);
        if ($changePasswordResult->execute([$password, $email, $code])){
            $getUserQuery = "SELECT `email` FROM `mechanic` WHERE `email` = ? AND `password` = ? AND `code` = ?";
            $getUserResult = $conn->prepare($getUserQuery);
            $getUserResult->execute([$email, $password, $code]);
            if ($getUserResult->fetch()){
                echo "congrats";
            }
            else{
                echo "sorry";
            }
        }
        else{
            echo "sorry";
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