<?php
    include "config/config.php";

    use google\appengine\api\cloud_storage\CloudStorageTools;

    if (isset($_POST['fname']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['lname']))
    {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
     
        updateImages($email, $conn);
        $updateUserQ = "UPDATE `mechanic` SET `fname` = ?, `lname` = ?, `phone` = ? WHERE `email` = ?";
        $updateUserR = $conn->prepare($updateUserQ);
        $updateUserR->execute([$fname, $lname, $phone, $email]);

        $conn->query("COMMIT");

        $findUserQuery = "SELECT * FROM `mechanic` WHERE `email` = ?";
		$findUserResult = $conn->prepare($findUserQuery);
		$findUserResult->execute([$email]);
        
        if ($user = $findUserResult->fetch()){
            echo json_encode($user);
        }
    }

    function updateImages($email, $conn){
        if (isset($_POST['dpImageData']) && isset($_POST['dpImageName'])){
            $imageName = $_POST['dpImageName'];
            $imageData = $_POST['dpImageData'];
            $imagePath = "userDps/$imageName.png";
            $bucket = "gs://".$_SERVER['BUCKET_NAME']."/$imagePath";
            file_put_contents($bucket, base64_decode($imageData));
            $dpServerUrl = CloudStorageTools::getPublicUrl($bucket, true);

            $updateUserQ = "UPDATE `mechanic` SET `image_path` = ? WHERE `email` = ?";
            $updateUserR = $conn->prepare($updateUserQ);
            $updateUserR->execute([$dpServerUrl, $email]);
        }
        if (isset($_POST['idImageData']) && isset($_POST['idImageName'])){
            $imageName = $_POST['idImageName'];
            $imageData = $_POST['idImageData'];
            $imagePath = "mechanicIDs/$imageName.png";
            $bucket = "gs://".$_SERVER['BUCKET_NAME']."/$imagePath";
            file_put_contents($bucket, base64_decode($imageData));
            $dpServerUrl = CloudStorageTools::getPublicUrl($bucket, true);

            $updateUserQ = "UPDATE `mechanic` SET `id_image_path` = ? WHERE `email` = ?";
            $updateUserR = $conn->prepare($updateUserQ);
            $updateUserR->execute([$dpServerUrl, $email]);
        }
        if (isset($_POST['qualificationImageData']) && isset($_POST['qualificationImageName'])){
            $imageName = $_POST['qualificationImageName'];
            $imageData = $_POST['qualificationImageData'];
            $imagePath = "mechanicQualifications/$imageName.png";
            $bucket = "gs://".$_SERVER['BUCKET_NAME']."/$imagePath";
            file_put_contents($bucket, base64_decode($imageData));
            $dpServerUrl = CloudStorageTools::getPublicUrl($bucket, true);

            $updateUserQ = "UPDATE `mechanic` SET `qualification_image_path` = ? WHERE `email` = ?";
            $updateUserR = $conn->prepare($updateUserQ);
            $updateUserR->execute([$dpServerUrl, $email]);
        }
    }
?>
