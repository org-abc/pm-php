<?php

	include_once("config/config.php");
	
	if(isset($_POST['lastUpdated'])){
	
		$lastUpdated = $_POST['lastUpdated'];
		
		if (isset($_POST['all'])){
			$getMechanicsQuery = "SELECT * FROM `mechanic`";
		}
		else{
			$getMechanicsQuery = "SELECT * FROM `mechanic` WHERE `status` = 'active'";
		}
		$getMechanicsResult = $conn->query($getMechanicsQuery);
		
		while ($Mechanic = $getMechanicsResult->fetch()){
			$Mechanics[] = $Mechanic;
		}
		echo json_encode($Mechanics);
	
	}else{
		echo "Ooooooooops, Something went wrong";
	}
?>