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
			$checkReqsQ = "SELECT * FROM `request` WHERE (`status` = 'accept' OR `status` = 'waiting') AND `user_email` = ?";
			$checkReqsR = $conn->prepare($checkReqsQ);
			$checkReqsR->execute([$email]);

			if ($req = $checkReqsR->fetch()){
				echo json_encode("user"->$user, "req"->$req);
			}
			else{
				echo json_encode(array("user"=>$user, "req"=>"empty"));
			}
        }
	}
	else
	{
		echo "failed";
	}
?>