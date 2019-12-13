<?php

	include_once("config/config.php");
	
	if(isset($_POST['reqId']) && isset($_POST['mechanicRating']) && isset($_POST['mechanicReview'])){
	
		$reqId = $_POST['reqId'];
		$mechanicRating = $_POST['mechanicRating'];
		$mechanicReview = $_POST['mechanicReview'];
        
        $getReqDetailsQuery = "SELECT `mechanic_email` FROM `request` WHERE `id` = ?";
        $getReqDetailsResult = $conn->prepare($getReqDetailsQuery);
        $getReqDetailsResult->execute([$reqId]);

        if ($req = $getReqDetailsResult->fetch()){
            $mechanicEmail = $req['mechanic_email'];

            $sendFeedbackQuery = "INSERT INTO `feedback`(`request_id`, `mechanic_review`, `mechanic_rating`, `mechanic_email`) VALUES(?, ?, ?, ?)";
            $sendFeedbackResult = $conn->prepare($sendFeedbackQuery);
            if ($sendFeedbackResult->execute([$reqId, $mechanicReview, $mechanicRating, $mechanicEmail])){
                $conn->query("COMMIT");
                $getFeedbackQuery = "SELECT * FROM `feedback` WHERE `request_id` = ?";
                $getFeedbackResult = $conn->prepare($getFeedbackQuery);
                if ($getFeedbackResult->execute([$reqId]) && $getFeedbackResult->fetch()){
                    echo "congrats";

                    $getMechanicRatingQuery = "SELECT AVG(`mechanic_rating`) AS `rating` FROM `feedback` WHERE `mechanic_email` = ?";
                    $getMechanicRatingResult = $conn->prepare($getMechanicRatingQuery);
                    $getMechanicRatingResult->execute([$mechanicEmail]);

                    if ($mechanicRating = $getMechanicRatingResult->fetch()){
                        $updateMechanicRatingQuery = "UPDATE `mechanic` SET `rating` = ? WHERE `email` = ?";
                        $updateMechanicRatingResult = $conn->prepare($updateMechanicRatingQuery);
                        $updateMechanicRatingResult->execute([$mechanicRating['rating'], $mechanicEmail]);
                    }
                }
                else{
                    echo "sorry";
                }
            }
            else{
                echo "sorry";
            }
        }
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}
?>