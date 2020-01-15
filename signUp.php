<?php
    include "config/config.php";

    if (isset($_POST['fname']) && isset($_POST['password']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['lname']) && isset($_POST['imageData']) && isset($_POST['imageName']))
    {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'];
        $passwd = hash('whirlpool',$_POST['password']);
        $email = $_POST['email'];
		$imageName = $_POST['imageName'];
		$imageData = $_POST['imageData'];
		$imagePath = "userDps/$imageName.png";
		$serverUrl = $pmWebsite."/$imagePath";
        $findUserQuery = "SELECT * FROM `user` WHERE `email` = ? ";
        $findUserResult = $conn->prepare($findUserQuery);
        $findUserResult->execute([$email]);
        if ($findUserResult->rowCount())
        {
            echo "Username already exists";
        }
        else{
            if (strlen($_POST['password']) >= 6)
            {
				$code = rand(1000, 9000);
                $url = $_SERVER['HTTP_HOST'] . str_replace("/signUp.php", "", $_SERVER['REQUEST_URI']);
                sendEmail($email,  "Code: ".$code, "Verification code");

                $addUserQuery = "INSERT INTO `user`(`fname`, `lname`, `phone`, `password`, `email`, `code`, `image_path`) VALUES(?, ?, ?, ?, ?, ?, ?)";
                $addUserResult = $conn->prepare($addUserQuery);
                $addUserResult->execute([$fname, $lname, $phone, $passwd, $email, $code, $serverUrl]);
                $conn->query("COMMIT");

                if ($addUserResult)
                {
                    file_put_contents($imagePath, base64_decode($imageData));
                }
                echo "verify";
            }
            else
            {
                echo "Password must be at least 6 characters";
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
