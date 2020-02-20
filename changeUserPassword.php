<?php
	include_once("config/config.php");
	
	if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['code'])){
	
        $email = $_POST['email'];
        $code = $_POST['code'];
        $password = hash('whirlpool', $_POST['password']);
		
        $changePasswordQuery = "UPDATE `user` SET `password` = ? WHERE `email` = ? AND `code` = ?";
        $changePasswordResult = $conn->prepare($changePasswordQuery);
        if ($changePasswordResult->execute([$password, $email, $code])){
            $getUserQuery = "SELECT `email` FROM `user` WHERE `email` = ? AND `password` = ? AND `code` = ?";
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
?>