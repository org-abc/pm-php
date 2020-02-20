<?php
    include_once("config/config.php");
    include_once("methods.php");

    use google\appengine\api\cloud_storage\CloudStorageTools;

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
		$bucket = "gs://".$_SERVER['BUCKET_NAME']."/$imagePath";
        $findUserQuery = "SELECT * FROM `user` WHERE `email` = ? ";
        $findUserResult = $conn->prepare($findUserQuery);
        $findUserResult->execute([$email]);

        file_put_contents($bucket, base64_decode($imageData));
        $serverUrl = CloudStorageTools::getPublicUrl($bucket, true);

        if ($findUserResult->rowCount())
        {
            // unlink($serverUrl);
            echo "Username already exists";
        }
        else{
            if (strlen($_POST['password']) >= 6)
            {
				$code = rand(1000, 9000);
                $url = $_SERVER['HTTP_HOST'] . str_replace("/signUp.php", "", $_SERVER['REQUEST_URI']);
                sendEmail($email,  "Code: ".$code, "Verification code", $companyEmail);

                $addUserQuery = "INSERT INTO `user`(`fname`, `lname`, `phone`, `password`, `email`, `code`, `image_path`) VALUES(?, ?, ?, ?, ?, ?, ?)";
                $addUserResult = $conn->prepare($addUserQuery);
                $addUserResult->execute([$fname, $lname, $phone, $passwd, $email, $code, $serverUrl]);
                $conn->query("COMMIT");

                echo "verify";
            }
            else
            {
                // unlink($serverUrl);
                echo "Password must be at least 6 characters";
            }
        }
    }
    else{
        echo "Oooops";
    }
?>
