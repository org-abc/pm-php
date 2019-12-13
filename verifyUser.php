<?php
	include_once "config/config.php";

	if (isset($_POST['code']) && isset($_POST['email']))
    {
        $code = $_POST['code'];
        $email = $_POST['email'];
        $findCodeQuery = "SELECT * FROM `user` WHERE `email` = ? and `code` = ?";
        $findCodeResult = $conn->prepare($findCodeQuery);
        $findCodeResult->execute([$email, $code]);
        if ($findCodeResult->rowCount())
        {
            $verifyUserQuery = "UPDATE `user` SET `verified` = true WHERE `email` = ? and `code` = ?";
            $verifyUserResult = $conn->prepare($verifyUserQuery);
            $verifyUserResult->execute([$email, $code]);
            $conn->query("COMMIT");
			
			echo "congrats";
        }
        else
        {
            echo "exists";
        }
    }
    else
    {
        echo "sorry";
    }
    
?>