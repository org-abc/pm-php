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
            $checkReqsQ = "SELECT * FROM `request` WHERE (`status` = 'accept' OR `status` = 'waiting' OR `status` = 'arrived') AND `mechanic_email` = ?";
			$checkReqsR = $conn->prepare($checkReqsQ);
			$checkReqsR->execute([$email]);

			if ($req = $checkReqsR->fetch()){
				echo json_encode(array("user"=>$user, "req"=>$req));
			}
			else{
				echo json_encode(array("user"=>$user, "req"=>"empty"));
			}
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