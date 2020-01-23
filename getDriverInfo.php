<?php
	include_once "config/config.php";

    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['token']))
    {
		$email = $_POST['email'];
		$token = $_POST['token'];
		$passwd = hash('whirlpool',$_POST['password']);
		$findUserQuery = "SELECT * FROM `mechanic` WHERE `email` = ? AND `password` = ?";
		$findUserResult = $conn->prepare($findUserQuery);
		$findUserResult->execute([$email, $passwd]);
        
        if ($user = $findUserResult->fetch()){
            echo json_encode($user);
		}
		insertToken($conn, $email, $passwd, $token);
	}
	else
	{
		echo "failed";
	}
	
	function insertToken($conn, $email, $passwd, $token){

		if ($token != ""){
			$insertTokenQ = "UPDATE `mechanic` SET `token` = ? WHERE `email` = ? AND `password` = ?";
			$insertTokenR = $conn->prepare($insertTokenQ);
			$insertTokenR->execute([$token, $email, $passwd]);
			$conn->query("COMMIT");
		}
	}
?>