<?php
    include "config/config.php";

    if (isset($_POST['fname']) && isset($_POST['password']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['lname']) && isset($_POST['minServiceFee']) && isset($_POST['imageData']) && isset($_POST['imageName']))
    {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'];
        $minServiceFee = $_POST['minServiceFee'];
		$imageName = $_POST['imageName'];
		$imageData = $_POST['imageData'];
		$imagePath = "mechanicDps/$imageName.png";
		$serverUrl = $pmWebsite."/$imagePath";
        $passwd = hash('whirlpool',$_POST['password']);
        $email = $_POST['email'];
        $findUserQuery = "SELECT * FROM `mechanic` WHERE `email` = ? ";
        $findUserResult = $conn->prepare($findUserQuery);
        $findUserResult->execute([$email]);
        if ($findUserResult->rowCount())
        {
            echo "Username already exists";
        }
        else{
            if (isset($_POST['pin'])){    
                $pin = $_POST['pin'];
                if ($pin != "4321")
                {
                    echo "wrongPin";
                }
            }
            else{
                $addUserQuery = "INSERT INTO `mechanic`(`fname`, `lname`, `phone`, `password`, `email`, `image_path`, `min_fee`, `status`) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
                $addUserResult = $conn->prepare($addUserQuery);
                $addUserResult->execute([$fname, $lname, $phone, $passwd, $email, $serverUrl, $minServiceFee, 'inactive']);
                if ($addUserResult)
                {
                    file_put_contents($imagePath, base64_decode($imageData));
                }
                $conn->query("COMMIT");
                sendEmail($email, "you are now a Mechanic at PM", "Congrats");
                
                echo "congrats";
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
