<?php
	include("../private/config.php");
	
	$plugin = $_GET['plugin'];
	function connectDB($user, $pass, $db) {
		try {	
			return(new PDO("mysql:host=localhost;dbname=" . $db . ";charset=utf8", $user, $pass));
		} catch(PDOException $ex) {
			return $ex;
		}
	}
	
	$db = connectDB($dbUser, $dbPass, $dbName);
	if ($db instanceof PDOException) {
		die ($db->getMessage());
	}
	$sql = "SELECT * FROM `Plugins` WHERE `Plugin` = :pl LIMIT 1"; 
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':pl', $plugin);
	$stmt->execute();
	$row = $stmt->fetch();
	if($stmt->rowCount() != 0) {
		header('Location: apply.php?e=6');
		die('false');
	}
	var_dump($row);
	if($row['auth'] == "1") {
		die("true");
	}
	//die("false");
?>