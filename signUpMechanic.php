<?php
    include_once("config/config.php");
    include_once("methods.php");

    use google\appengine\api\cloud_storage\CloudStorageTools;

    if (isset($_POST['fname']) && isset($_POST['password']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['lname']) && isset($_POST['minServiceFee']) && isset($_POST['imageData']) && isset($_POST['imageName']) && isset($_POST['IdImageData']) && isset($_POST['IdImageName']))
    {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'];
        $minServiceFee = $_POST['minServiceFee'];

		$imageName = $_POST['imageName'];
		$imageData = $_POST['imageData'];
		$imagePath = "mechanicDps/$imageName.png";
		$bucket = "gs://".$_SERVER['BUCKET_NAME']."/$imagePath";
        file_put_contents($bucket, base64_decode($imageData));
        $serverUrl = CloudStorageTools::getPublicUrl($bucket, true);
        
		$IdImageName = $_POST['IdImageName'];
		$IdImageData = $_POST['IdImageData'];
		$IdImagePath = "mechanicIDs/$IdImageName.png";
		$IdBucket = "gs://".$_SERVER['BUCKET_NAME']."/$IdImagePath";
        file_put_contents($IdBucket, base64_decode($IdImageData));
        $IdServerUrl = CloudStorageTools::getPublicUrl($IdBucket, true);
        
        if (isset($_POST['QualificationImageData']) && isset($_POST['QualificationImageName'])){
            $QualificationImageName = $_POST['QualificationImageName'];
            $QualificationImageData = $_POST['QualificationImageData'];
            $QualificationImagePath = "mechanicQualifications/$QualificationImageName.png";
            $QualificationBucket = "gs://".$_SERVER['BUCKET_NAME']."/$QualificationImagePath";
            file_put_contents($QualificationBucket, base64_decode($QualificationImageData));
            $QualificationServerUrl = CloudStorageTools::getPublicUrl($QualificationBucket, true);
        }

        $passwd = hash('whirlpool',$_POST['password']);
        $email = $_POST['email'];
        $findUserQuery = "SELECT * FROM `mechanic` WHERE `email` = ? ";
        $findUserResult = $conn->prepare($findUserQuery);
        $findUserResult->execute([$email]);
        if ($findUserResult->rowCount())
        {
            // unlink($serverUrl);
            // unlink($IdServerUrl);
            // unlink($QualificationServerUrl);
            echo "Username already exists";
        }
        else{
            if (isset($_POST['pin'])){    
                $pin = $_POST['pin'];
                if ($pin != "4321")
                {
                    // unlink($serverUrl);
                    // unlink($IdServerUrl);
                    // unlink($QualificationServerUrl);
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

                $conn->query("COMMIT");
                sendEmail($email, "you are now a Mechanic at PM", "Congrats", $companyEmail);
                sendEmail($companyEmail, "A new mechanic signed up. Please go to review him", "Another one", $companyEmail);
                
                echo "congrats";
            }
        }
    }
?>
