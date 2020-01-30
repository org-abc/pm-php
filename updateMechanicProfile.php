<?php
    include "config/config.php";

    use google\appengine\api\cloud_storage\CloudStorageTools;

    if (isset($_POST['fname']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['lname']))
    {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
     
        if (isset($_POST['imageData']) && isset($_POST['imageName'])){
            $imageName = $_POST['imageName'];
            $imageData = $_POST['imageData'];
            $imagePath = "userDps/$imageName.png";
            $bucket = "gs://".$_SERVER['BUCKET_NAME']."/$imagePath";
            file_put_contents($bucket, base64_decode($imageData));
            $serverUrl = CloudStorageTools::getPublicUrl($bucket, true);

            $updateUserQ = "UPDATE `mechanic` SET `fname` = ?, `lname` = ?, `phone` = ?, `image_path` = ? WHERE `email` = ?";
            $updateUserR = $conn->prepare($updateUserQ);
            $updateUserR->execute([$fname, $lname, $phone, $serverUrl, $email]);
        }
        else{
            $updateUserQ = "UPDATE `mechanic` SET `fname` = ?, `lname` = ?, `phone` = ? WHERE `email` = ?";
            $updateUserR = $conn->prepare($updateUserQ);
            $updateUserR->execute([$fname, $lname, $phone, $email]);
        }
        $conn->query("COMMIT");

        $findUserQuery = "SELECT * FROM `mechanic` WHERE `email` = ?";
		$findUserResult = $conn->prepare($findUserQuery);
		$findUserResult->execute([$email]);
        
        if ($user = $findUserResult->fetch()){
            echo json_encode($user);
        }
    }
?>
