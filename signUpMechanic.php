<?php
    include "config/config.php";

    if (isset($_POST['fname']) && isset($_POST['password']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['lname']) && isset($_POST['minServiceFee']) && isset($_POST['imageData']) && isset($_POST['imageName']) && isset($_POST['IdImageData']) && isset($_POST['IdImageName']))
    {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'];
        $minServiceFee = $_POST['minServiceFee'];

		$imageName = $_POST['imageName'];
		$imageData = $_POST['imageData'];
		$imagePath = "mechanicDps/$imageName.png";
        $serverUrl = $pmWebsite."/$imagePath";
        
		$IdImageName = $_POST['IdImageName'];
		$IdImageData = $_POST['IdImageData'];
		$IdImagePath = "mechanicIDs/$IdImageName.png";
        $IdServerUrl = $pmWebsite."/$IdImagePath";
        
        if (isset($_POST['QualificationImageData']) && isset($_POST['QualificationImageName'])){
            $QualificationImageName = $_POST['QualificationImageName'];
            $QualificationImageData = $_POST['QualificationImageData'];
            $QualificationImagePath = "mechanicQualifications/$QualificationImageName.png";
            $QualificationServerUrl = $pmWebsite."/$QualificationImagePath";
        }

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
                if (isset($_POST['QualificationImageData']) && isset($_POST['QualificationImageName'])){
                    $addUserQuery = "INSERT INTO `mechanic`(`fname`, `lname`, `phone`, `password`, `email`, `image_path`, `min_fee`, `status`, `id_image_path`, `qualification_image_path`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $addUserResult = $conn->prepare($addUserQuery);
                    $addUserResult->execute([$fname, $lname, $phone, $passwd, $email, $serverUrl, $minServiceFee, 'inactive', $IdServerUrl, $QualificationServerUrl]);
                }
                else{
                    $addUserQuery = "INSERT INTO `mechanic`(`fname`, `lname`, `phone`, `password`, `email`, `image_path`, `min_fee`, `status`, `id_image_path`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $addUserResult = $conn->prepare($addUserQuery);
                    $addUserResult->execute([$fname, $lname, $phone, $passwd, $email, $serverUrl, $minServiceFee, 'inactive', $IdServerUrl]);
                }

                if ($addUserResult)
                {
                    file_put_contents($imagePath, base64_decode($imageData));
                    file_put_contents($IdImagePath, base64_decode($IdImageData));
                    if (isset($_POST['QualificationImageData']) && isset($_POST['QualificationImageName'])){
                        file_put_contents($QualificationImagePath, base64_decode($QualificationImageData));
                    }
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
