<?php
	include_once "config/config.php";

    if (isset($_POST['email']) && isset($_POST['password']))
    {
		$email = $_POST['email'];
		$passwd = hash('whirlpool',$_POST['password']);
		$findUserQuery = "SELECT * FROM `user` WHERE `email` = ? AND `password` = ?";
		$findUserResult = $conn->prepare($findUserQuery);
		$findUserResult->execute([$email, $passwd]);
        
        if ($user = $findUserResult->fetch()){
            echo json_encode($user);
        }
	}
	else
	{
		echo "failed";
	}
?>